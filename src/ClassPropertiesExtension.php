<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;

abstract class ClassPropertiesExtension implements PropertiesClassReflectionExtension
{
    private ReflectionProvider $reflectionProvider;

    /**
     * Constructor.
     *
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider Reflection provider.
     */
    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    /**
     * @inheritDoc
     */
    public function hasProperty(
        ClassReflection $classReflection,
        string $propertyName,
    ): bool {
        if (!array_filter($this->getContainingClassNames(), [$classReflection, 'is'])) {
            return false;
        }

        // Properties declared with a `@property` tag are left to PHPStan, which handles the tags
        // after the extensions of this package and would otherwise never see them.
        if (ClassDeclarationReader::hasPropertyTag($classReflection, $propertyName)) {
            return false;
        }

        $propertyClassName = $this->getClassNameFromPropertyName($propertyName);

        return $this->reflectionProvider->hasClass($propertyClassName)
            && $this->reflectionProvider->getClass($propertyClassName)
                ->is($this->getPropertyParentClassName());
    }

    /**
     * @inheritDoc
     */
    public function getProperty(
        ClassReflection $classReflection,
        string $propertyName,
    ): PropertyReflection {
        return new PublicReadOnlyPropertyReflection(
            $this->getClassNameFromPropertyName($propertyName),
            $classReflection,
        );
    }

    /**
     * Get the class name of the type of property.
     */
    abstract protected function getPropertyParentClassName(): string;

    /**
     * Get the class names which can contain the property.
     *
     * @return array<string>
     */
    abstract protected function getContainingClassNames(): array;

    /**
     * Return the class name from the property name.
     */
    abstract protected function getClassNameFromPropertyName(
        string $propertyName,
    ): string;
}
