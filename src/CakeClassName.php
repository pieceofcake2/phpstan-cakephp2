<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

/**
 * Resolves the global names of the CakePHP 2 classes.
 *
 * pieceofcake2/cakephp may declare its classes in the `Cake` namespace and keep the global names of
 * CakePHP 2 as aliases (`Component` for `Cake\Controller\Component`). An alias is declared when the
 * class is loaded, and before that, `ClassReflection::is('Component')` does not match the namespaced
 * class. The extensions resolve a global name with the reflection provider, which loads the class,
 * and compare with the declared name. With a framework of global classes, the name is unchanged.
 */
final class CakeClassName
{
    /**
     * Returns the declared name of the class that a name refers to, or the name itself if PHPStan does
     * not know the class.
     *
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider Reflection provider.
     * @param string $name Class name, e.g. `Component`.
     * @return string
     */
    public static function resolve(ReflectionProvider $reflectionProvider, string $name): string
    {
        return $reflectionProvider->hasClass($name) ? $reflectionProvider->getClass($name)->getName() : $name;
    }

    /**
     * Returns whether a class is, extends or implements the class that a name refers to.
     *
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider Reflection provider.
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class to check.
     * @param string $name Class name, e.g. `Component`.
     * @return bool
     */
    public static function is(
        ReflectionProvider $reflectionProvider,
        ClassReflection $classReflection,
        string $name,
    ): bool {
        return $classReflection->is(self::resolve($reflectionProvider, $name));
    }

    /**
     * Returns whether a class is, extends or implements one of the classes that names refer to.
     *
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider Reflection provider.
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class to check.
     * @param array<string> $names Class names, e.g. `['Controller', 'Component']`.
     * @return bool
     */
    public static function isAnyOf(
        ReflectionProvider $reflectionProvider,
        ClassReflection $classReflection,
        array $names,
    ): bool {
        foreach ($names as $name) {
            if (self::is($reflectionProvider, $classReflection, $name)) {
                return true;
            }
        }

        return false;
    }
}
