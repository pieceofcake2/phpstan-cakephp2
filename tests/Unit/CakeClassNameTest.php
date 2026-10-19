<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Unit;

use PHPStan\Testing\PHPStanTestCase;
use PHPStanCakePHP2\CakeClassName;

/**
 * pieceofcake2/cakephp may declare its classes in the `Cake` namespace with the global names of
 * CakePHP 2 as aliases. These tests use an alias of a namespaced class in the same way.
 */
class CakeClassNameTest extends PHPStanTestCase
{
    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!class_exists('CakeClassNameTestCase', false)) {
            class_alias(PHPStanTestCase::class, 'CakeClassNameTestCase');
        }
    }

    /**
     * @return void
     */
    public function testResolve(): void
    {
        $reflectionProvider = $this->createReflectionProvider();

        $this->assertSame(PHPStanTestCase::class, CakeClassName::resolve($reflectionProvider, 'CakeClassNameTestCase'));
        $this->assertSame(self::class, CakeClassName::resolve($reflectionProvider, self::class));
        $this->assertSame('UnknownClass', CakeClassName::resolve($reflectionProvider, 'UnknownClass'));
    }

    /**
     * @return void
     */
    public function testIs(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(self::class);

        $this->assertTrue(CakeClassName::is($reflectionProvider, $classReflection, 'CakeClassNameTestCase'));
        $this->assertTrue(CakeClassName::is($reflectionProvider, $classReflection, PHPStanTestCase::class));
        $this->assertFalse(CakeClassName::is($reflectionProvider, $classReflection, 'UnknownClass'));
    }

    /**
     * @return void
     */
    public function testIsAnyOf(): void
    {
        $reflectionProvider = $this->createReflectionProvider();
        $classReflection = $reflectionProvider->getClass(self::class);

        $this->assertTrue(CakeClassName::isAnyOf($reflectionProvider, $classReflection, ['UnknownClass', 'CakeClassNameTestCase']));
        $this->assertFalse(CakeClassName::isAnyOf($reflectionProvider, $classReflection, ['UnknownClass']));
        $this->assertFalse(CakeClassName::isAnyOf($reflectionProvider, $classReflection, []));
    }
}
