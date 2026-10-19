<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;

final class ClassComponentsExtension implements PropertiesClassReflectionExtension
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
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (!CakeClassName::isAnyOf($this->reflectionProvider, $classReflection, $this->getContainingClassNames())) {
            return false;
        }

        if (!in_array($propertyName, ClassDeclarationReader::getListedNames($classReflection, 'components'), true)) {
            return false;
        }

        if (ClassDeclarationReader::hasPropertyTag($classReflection, $propertyName)) {
            return false;
        }

        $propertyClassName = $this->getClassNameFromPropertyName($propertyName);

        return $this->reflectionProvider->hasClass($propertyClassName)
            && CakeClassName::is(
                $this->reflectionProvider,
                $this->reflectionProvider->getClass($propertyClassName),
                'Component',
            );
    }

    /**
     * @inheritDoc
     */
    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return new PublicReadOnlyPropertyReflection(
            $this->getClassNameFromPropertyName($propertyName),
            $classReflection,
        );
    }

    /**
     * @return array<string>
     */
    private function getContainingClassNames(): array
    {
        return [
            'Controller',
            'Component',
        ];
    }

    /**
     * Returns the component class name for the given property name.
     *
     * @param string $propertyName Property name, e.g. `Session` or `SessionComponent`.
     * @return string
     */
    private function getClassNameFromPropertyName(string $propertyName): string
    {
        return str_contains($propertyName, 'Component') ? $propertyName : $propertyName . 'Component';
    }
}
