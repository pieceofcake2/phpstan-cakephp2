<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;

/**
 * Wraps {@link ModelBehavior} {@link MethodReflection}s to remove their first
 * parameter (which should be the {@link Model}).
 */
final class ModelBehaviorMethodWrapper implements MethodReflection
{
    private MethodReflection $wrappedMethod;

    /**
     * Constructor.
     *
     * @param \PHPStan\Reflection\MethodReflection $wrappedMethod Behavior method to wrap.
     */
    public function __construct(MethodReflection $wrappedMethod)
    {
        $this->wrappedMethod = $wrappedMethod;
    }

    /**
     * @inheritDoc
     */
    public function getDeclaringClass(): ClassReflection
    {
        return $this->wrappedMethod->getDeclaringClass();
    }

    /**
     * @inheritDoc
     */
    public function isStatic(): bool
    {
        return $this->wrappedMethod->isStatic();
    }

    /**
     * @inheritDoc
     */
    public function isPrivate(): bool
    {
        return $this->wrappedMethod->isPrivate();
    }

    /**
     * @inheritDoc
     */
    public function isPublic(): bool
    {
        return $this->wrappedMethod->isPublic();
    }

    /**
     * @inheritDoc
     */
    public function getDocComment(): ?string
    {
        return $this->wrappedMethod->getDocComment();
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->wrappedMethod->getName();
    }

    /**
     * @inheritDoc
     */
    public function getPrototype(): ClassMemberReflection
    {
        return $this->wrappedMethod->getPrototype();
    }

    /**
     * @return array<\PHPStan\Reflection\ParametersAcceptor>
     */
    public function getVariants(): array
    {
        return array_map(static function (ParametersAcceptor $variant) {
            $parameters = $variant->getParameters();
            array_shift($parameters);

            return new FunctionVariant(
                $variant->getTemplateTypeMap(),
                $variant->getResolvedTemplateTypeMap(),
                $parameters,
                $variant->isVariadic(),
                $variant->getReturnType(),
            );
        }, $this->wrappedMethod->getVariants());
    }

    /**
     * @inheritDoc
     */
    public function isDeprecated(): TrinaryLogic
    {
        return $this->wrappedMethod->isDeprecated();
    }

    /**
     * @inheritDoc
     */
    public function getDeprecatedDescription(): ?string
    {
        return $this->wrappedMethod->getDeprecatedDescription();
    }

    /**
     * @inheritDoc
     */
    public function isFinal(): TrinaryLogic
    {
        return $this->wrappedMethod->isFinal();
    }

    /**
     * @inheritDoc
     */
    public function isInternal(): TrinaryLogic
    {
        return $this->wrappedMethod->isInternal();
    }

    /**
     * @inheritDoc
     */
    public function getThrowType(): ?Type
    {
        return $this->wrappedMethod->getThrowType();
    }

    /**
     * @inheritDoc
     */
    public function hasSideEffects(): TrinaryLogic
    {
        return $this->wrappedMethod->hasSideEffects();
    }
}
