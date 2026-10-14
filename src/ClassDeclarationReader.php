<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\ClassReflection;

/**
 * Reads CakePHP 2 declarations from a class and its ancestors, such as the names listed in
 * `$components` or `$tasks` and the `@property` tags of the class.
 */
final class ClassDeclarationReader
{
    /**
     * Returns the names listed in the default value of an array property, such as `$components` or `$tasks`,
     * of the class and its parents.
     *
     * Names defined with settings (`'Name' => [...]`) and in plugin syntax (`'Plugin.Name'`) are supported, and
     * the plugin prefix is removed. Names defined as `Name::class` are returned as the class name.
     *
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection.
     * @param string $listPropertyName Name of the array property, e.g. `components`.
     * @return list<string>
     */
    public static function getListedNames(ClassReflection $classReflection, string $listPropertyName): array
    {
        $names = [];

        foreach (array_merge([$classReflection], $classReflection->getParents()) as $class) {
            if (!$class->hasNativeProperty($listPropertyName)) {
                continue;
            }

            $defaultValue = $class->getNativeProperty($listPropertyName)
                ->getNativeReflection()
                ->getDefaultValueExpression();

            if (!$defaultValue instanceof Array_) {
                continue;
            }

            foreach ($defaultValue->items as $item) {
                // Entries with settings are defined as `'Name' => [...settings]`.
                $entry = $item->key ?? $item->value;

                if ($entry instanceof String_) {
                    $names[] = self::removePluginPrefix($entry->value);

                    continue;
                }

                if ($entry instanceof ClassConstFetch && $entry->class instanceof FullyQualified) {
                    $names[] = $entry->class->toString();
                }
            }
        }

        return $names;
    }

    /**
     * Returns whether the class, one of its parents, interfaces or traits declares the property
     * with a `@property`, `@property-read` or `@property-write` tag.
     *
     * Such properties are left to PHPStan, which handles the tags after the extensions of this package.
     *
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection.
     * @param string $propertyName Property name.
     * @return bool
     */
    public static function hasPropertyTag(ClassReflection $classReflection, string $propertyName): bool
    {
        $classes = array_merge(
            [$classReflection],
            $classReflection->getParents(),
            array_values($classReflection->getInterfaces()),
        );

        foreach ($classes as $class) {
            if (array_key_exists($propertyName, $class->getPropertyTags())) {
                return true;
            }

            foreach ($class->getTraits(true) as $trait) {
                if (array_key_exists($propertyName, $trait->getPropertyTags())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns the name without its plugin prefix, e.g. `DebugKit.Toolbar` => `Toolbar`.
     *
     * @param string $name Name, optionally in plugin syntax.
     * @return string
     */
    public static function removePluginPrefix(string $name): string
    {
        $position = strrpos($name, '.');

        return $position === false ? $name : substr($name, $position + 1);
    }
}
