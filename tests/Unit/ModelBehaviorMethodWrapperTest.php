<?php
declare(strict_types=1);

namespace PHPStanCakePHP2\Test\Unit;

use PHPStan\Testing\PHPStanTestCase;
use PHPStan\Type\VerbosityLevel;
use PHPStanCakePHP2\ModelBehaviorMethodWrapper;

class ModelBehaviorMethodWrapperTest extends PHPStanTestCase
{
    /**
     * @return void
     */
    public function testDelegatesToWrappedMethod(): void
    {
        $method = self::createReflectionProvider()
            ->getClass('BasicBehavior')
            ->getNativeMethod('behaviorMethod');
        $wrapper = new ModelBehaviorMethodWrapper($method);

        $this->assertSame($method->getDeclaringClass(), $wrapper->getDeclaringClass());
        $this->assertSame('behaviorMethod', $wrapper->getName());
        $this->assertSame($method->isStatic(), $wrapper->isStatic());
        $this->assertSame($method->isPrivate(), $wrapper->isPrivate());
        $this->assertSame($method->isPublic(), $wrapper->isPublic());
        $this->assertSame($method->getDocComment(), $wrapper->getDocComment());
        $this->assertSame($method->getPrototype(), $wrapper->getPrototype());
        $this->assertSame($method->isDeprecated(), $wrapper->isDeprecated());
        $this->assertSame($method->getDeprecatedDescription(), $wrapper->getDeprecatedDescription());
        $this->assertSame($method->isFinal(), $wrapper->isFinal());
        $this->assertSame($method->isInternal(), $wrapper->isInternal());
        $this->assertSame($method->getThrowType(), $wrapper->getThrowType());
        $this->assertSame($method->hasSideEffects(), $wrapper->hasSideEffects());
    }

    /**
     * @return void
     */
    public function testRemovesModelParameterFromVariants(): void
    {
        $method = self::createReflectionProvider()
            ->getClass('BasicBehavior')
            ->getNativeMethod('behaviorMethod');
        $wrapper = new ModelBehaviorMethodWrapper($method);

        $variants = $wrapper->getVariants();
        $this->assertCount(1, $variants);

        $parameters = $variants[0]->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertSame('string', $parameters[0]->getName());
        $this->assertSame('string', $variants[0]->getReturnType()->describe(VerbosityLevel::precise()));
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
