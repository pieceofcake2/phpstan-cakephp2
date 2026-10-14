<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use Inflector;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\TrinaryLogic;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use PHPStanCakePHP2\Service\SchemaService;

/**
 * Resolves the type returned by `ClassRegistry::init($class, $strict = false)`.
 *
 * When `$class` is a single class (a string, or an array without a `0` key, usually with a `class` key):
 *
 * - A class known to PHPStan resolves to an instance of that class (with `$strict`, only when it
 *   is a model, since `init()` returns false for other classes).
 * - A class not known to PHPStan resolves to `Model` when a table for it is defined in a schema
 *   file and `$strict` is false, since `init()` then creates an `AppModel` for it.
 * - Any other class resolves to `object` when `$strict` is false, since `init()` never returns false
 *   then: it creates an instance of the class, or an `AppModel` when the class does not exist. The
 *   instance is not necessarily a model, as `init()` instantiates any existing class and returns any
 *   object registered under the same alias (e.g. behaviors).
 * - With `$strict`, `init()` returns false when the class does not exist or is not a model, so any
 *   other class resolves to `object|false`.
 *
 * Other forms of `$class` (lists of configurations, which make `init()` return true or false, or
 * values which may be such lists) resolve to the declared `bool|object`.
 */
class ClassRegistryInitExtension implements DynamicStaticMethodReturnTypeExtension
{
    private ReflectionProvider $reflectionProvider;

    private SchemaService $schemaService;

    /**
     * Constructor.
     *
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider Reflection provider.
     * @param \PHPStanCakePHP2\Service\SchemaService $schemaService Schema service.
     */
    public function __construct(
        ReflectionProvider $reflectionProvider,
        SchemaService $schemaService,
    ) {
        $this->reflectionProvider = $reflectionProvider;
        $this->schemaService = $schemaService;
    }

    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return 'ClassRegistry';
    }

    /**
     * @inheritDoc
     */
    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'init';
    }

    /**
     * @inheritDoc
     */
    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope,
    ): ?Type {
        $args = $methodCall->getArgs();
        if (!isset($args[0])) {
            return $this->getDefaultType();
        }

        $classType = $this->getClassType($scope->getType($args[0]->value));
        if ($classType === null) {
            return $this->getDefaultType();
        }

        $strict = $this->isStrict(isset($args[1]) ? $scope->getType($args[1]->value) : null);

        $types = [];
        if (!$strict->yes()) {
            $types[] = $this->resolveClass($classType, false);
        }
        if (!$strict->no()) {
            $types[] = $this->resolveClass($classType, true);
        }

        return TypeCombinator::union(...$types);
    }

    /**
     * Returns the type of the class name given to `ClassRegistry::init()`, or null when the call is
     * not known to create a single instance.
     *
     * The class can be given as a string (`'Model'`, `'Plugin.Model'`), or as an array with a `class` key
     * (`['class' => 'Plugin.Model', 'alias' => 'Alias']`). Arrays with a `0` key are lists of
     * configurations. Any other value creates a single instance too, as `init()` wraps it in an array.
     *
     * @param \PHPStan\Type\Type $type Type of the first argument.
     * @return \PHPStan\Type\Type|null
     */
    private function getClassType(Type $type): ?Type
    {
        if ($type->isArray()->no()) {
            return $type;
        }

        if (!$type->isArray()->yes() || !$type->hasOffsetValueType(new ConstantIntegerType(0))->no()) {
            return null;
        }

        $classKey = new ConstantStringType('class');
        if (!$type->hasOffsetValueType($classKey)->yes()) {
            return new MixedType();
        }

        return $type->getOffsetValueType($classKey);
    }

    /**
     * Returns whether the `$strict` argument is true.
     *
     * @param \PHPStan\Type\Type|null $type Type of the second argument, or null when it is omitted.
     * @return \PHPStan\TrinaryLogic
     */
    private function isStrict(?Type $type): TrinaryLogic
    {
        if ($type === null) {
            return TrinaryLogic::createNo();
        }

        if ($type->isTrue()->yes()) {
            return TrinaryLogic::createYes();
        }

        if ($type->isFalse()->yes() || $type->isNull()->yes()) {
            return TrinaryLogic::createNo();
        }

        return TrinaryLogic::createMaybe();
    }

    /**
     * Returns the type `ClassRegistry::init()` returns for the given class name.
     *
     * @param \PHPStan\Type\Type $classType Type of the class name.
     * @param bool $strict Whether `$strict` is true.
     * @return \PHPStan\Type\Type
     */
    private function resolveClass(Type $classType, bool $strict): Type
    {
        $constantStrings = $classType->getConstantStrings();

        if ($constantStrings === []) {
            $objectType = $classType->getClassStringObjectType();

            if ($classType->isClassString()->yes() && $objectType->getObjectClassNames() !== []) {
                return $this->resolveObject($objectType, $strict);
            }

            return $this->getUnresolvedType($strict);
        }

        $types = [];

        foreach ($constantStrings as $constantString) {
            $className = ClassDeclarationReader::removePluginPrefix($constantString->getValue());

            if ($this->reflectionProvider->hasClass($className)) {
                $types[] = $this->resolveObject(new ObjectType($className), $strict);
            } elseif (!$strict && $this->schemaService->hasTable(Inflector::tableize($className))) {
                $types[] = new ObjectType('Model');
            } else {
                $types[] = $this->getUnresolvedType($strict);
            }
        }

        return TypeCombinator::union(...$types);
    }

    /**
     * Returns the type `ClassRegistry::init()` returns for a class known to PHPStan.
     *
     * @param \PHPStan\Type\Type $objectType Instance of the class.
     * @param bool $strict Whether `$strict` is true.
     * @return \PHPStan\Type\Type
     */
    private function resolveObject(Type $objectType, bool $strict): Type
    {
        if (!$strict || (new ObjectType('Model'))->isSuperTypeOf($objectType)->yes()) {
            return $objectType;
        }

        return $this->getUnresolvedType($strict);
    }

    /**
     * Returns the type `ClassRegistry::init()` returns for a single class which cannot be resolved.
     *
     * @param bool $strict Whether `$strict` is true.
     * @return \PHPStan\Type\Type
     */
    private function getUnresolvedType(bool $strict): Type
    {
        if (!$strict) {
            return new ObjectWithoutClassType();
        }

        return new UnionType([
            new ObjectWithoutClassType(),
            new ConstantBooleanType(false),
        ]);
    }

    /**
     * Returns the type `ClassRegistry::init()` is declared to return.
     *
     * @return \PHPStan\Type\Type
     */
    private function getDefaultType(): Type
    {
        return new UnionType([
            new BooleanType(),
            new ObjectWithoutClassType(),
        ]);
    }
}
