<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Unit;

use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\VerbosityLevel;
use PHPStanCakePHP2\PublicReadOnlyPropertyReflection;

class PublicReadOnlyPropertyReflectionTest extends PHPStanTestCase
{
    /**
     * @return void
     */
    public function testDescribesPublicReadOnlyProperty(): void
    {
        $declaringClass = self::createReflectionProvider()->getClass('BasicController');
        $property = new PublicReadOnlyPropertyReflection('BasicModel', $declaringClass);

        $this->assertSame($declaringClass, $property->getDeclaringClass());
        $this->assertSame('BasicModel', $property->getReadableType()->describe(VerbosityLevel::precise()));
        $this->assertSame('BasicModel', $property->getWritableType()->describe(VerbosityLevel::precise()));
        $this->assertFalse($property->isStatic());
        $this->assertFalse($property->isPrivate());
        $this->assertTrue($property->isPublic());
        $this->assertNull($property->getDocComment());
        $this->assertFalse($property->canChangeTypeAfterAssignment());
        $this->assertTrue($property->isReadable());
        $this->assertFalse($property->isWritable());
        $this->assertTrue($property->isDeprecated()->no());
        $this->assertNull($property->getDeprecatedDescription());
        $this->assertTrue($property->isInternal()->no());
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
