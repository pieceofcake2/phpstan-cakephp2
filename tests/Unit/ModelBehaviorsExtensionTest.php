<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Unit;

use Exception;
use PHPStan\Testing\PHPStanTestCase;
use PHPStanCakePHP2\ModelBehaviorsExtension;

class ModelBehaviorsExtensionTest extends PHPStanTestCase
{
    /**
     * @return void
     */
    public function testHasMethod(): void
    {
        $extension = self::getContainer()->getByType(ModelBehaviorsExtension::class);
        $reflectionProvider = self::createReflectionProvider();

        $this->assertTrue($extension->hasMethod($reflectionProvider->getClass('BasicModel'), 'behaviorMethod'));
        $this->assertFalse($extension->hasMethod($reflectionProvider->getClass('BasicModel'), 'unknownMethod'));
        $this->assertFalse($extension->hasMethod($reflectionProvider->getClass('BasicController'), 'behaviorMethod'));
    }

    /**
     * @return void
     */
    public function testGetMethod(): void
    {
        $extension = self::getContainer()->getByType(ModelBehaviorsExtension::class);
        $method = $extension->getMethod(self::createReflectionProvider()->getClass('BasicModel'), 'behaviorMethod');

        $this->assertSame('behaviorMethod', $method->getName());
        $this->assertSame('BasicBehavior', $method->getDeclaringClass()->getName());
    }

    /**
     * @return void
     */
    public function testGetMethodThrowsForUnknownMethod(): void
    {
        $extension = self::getContainer()->getByType(ModelBehaviorsExtension::class);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Method not found');

        $extension->getMethod(self::createReflectionProvider()->getClass('BasicModel'), 'unknownMethod');
    }

    /**
     * @inheritDoc
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../Feature/data/phpstan.neon',
        ];
    }
}
