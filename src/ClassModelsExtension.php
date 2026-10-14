<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Reflection\ClassReflection;

/**
 * Adds {@link Model}s as properties to {@link Controller}s, {@link Model}s and {@link Shell}s.
 */
final class ClassModelsExtension extends ClassPropertiesExtension
{
    /**
     * @inheritDoc
     */
    public function hasProperty(
        ClassReflection $classReflection,
        string $propertyName,
    ): bool {
        // A task listed in the `$tasks` of a shell or task, e.g. the `Model` task of bake, takes precedence
        // over a model of the same name.
        if (
            $classReflection->is('Shell')
            && in_array($propertyName, ClassDeclarationReader::getListedNames($classReflection, 'tasks'), true)
        ) {
            return false;
        }

        return parent::hasProperty($classReflection, $propertyName);
    }

    /**
     * @inheritDoc
     */
    protected function getPropertyParentClassName(): string
    {
        return 'Model';
    }

    /**
     * @return array<string>
     */
    protected function getContainingClassNames(): array
    {
        return [
            'Controller',
            'Model',
            'Shell',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getClassNameFromPropertyName(
        string $propertyName,
    ): string {
        return $propertyName;
    }
}
