<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

/**
 * Adds {@link Model}s as properties to {@link Shell}s.
 */
final class ClassModelsExtension extends ClassPropertiesExtension
{
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
