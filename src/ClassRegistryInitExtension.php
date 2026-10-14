<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use Inflector;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStanCakePHP2\Service\SchemaService;

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

        $type = $scope->getType($args[0]->value);

        foreach ($this->getClassNames($type) as $className) {
            if ($this->reflectionProvider->hasClass($className)) {
                return new ObjectType($className);
            }

            if ($this->schemaService->hasTable(Inflector::tableize($className))) {
                return new ObjectType('Model');
            }
        }

        return $this->getDefaultType();
    }

    /**
     * Returns the class names given to `ClassRegistry::init()`, without their plugin prefix.
     *
     * The class can be given as a string (`'Model'`, `'Plugin.Model'`), or as an array with a `class` key
     * (`['class' => 'Plugin.Model', 'alias' => 'Alias']`).
     *
     * @param \PHPStan\Type\Type $type Type of the first argument.
     * @return list<string>
     */
    private function getClassNames(Type $type): array
    {
        $classTypes = [$type];

        foreach ($type->getConstantArrays() as $constantArray) {
            $classKey = new ConstantStringType('class');

            if ($constantArray->hasOffsetValueType($classKey)->yes()) {
                $classTypes[] = $constantArray->getOffsetValueType($classKey);
            }
        }

        $classNames = [];

        foreach ($classTypes as $classType) {
            foreach ($classType->getConstantStrings() as $constantString) {
                $classNames[] = ClassDeclarationReader::removePluginPrefix($constantString->getValue());
            }
        }

        return $classNames;
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
