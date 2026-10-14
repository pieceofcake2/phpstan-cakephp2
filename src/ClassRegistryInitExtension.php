<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use Inflector;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
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

        foreach ($type->getConstantStrings() as $constantString) {
            $value = $constantString->getValue();

            if ($this->reflectionProvider->hasClass($value)) {
                return new ObjectType($value);
            }

            if ($this->schemaService->hasTable(Inflector::tableize($value))) {
                return new ObjectType('Model');
            }
        }

        return $this->getDefaultType();
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
