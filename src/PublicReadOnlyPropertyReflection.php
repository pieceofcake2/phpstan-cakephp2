<?php
declare(strict_types=1);

namespace PHPStanCakePHP2;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class PublicReadOnlyPropertyReflection implements PropertyReflection
{
    private string $name;

    private ClassReflection $declaringClass;

    /**
     * Constructor.
     *
     * @param string $name Class name of the property type.
     * @param \PHPStan\Reflection\ClassReflection $declaringClass Class declaring the property.
     */
    public function __construct(string $name, ClassReflection $declaringClass)
    {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
    }

    /**
     * @inheritDoc
     */
    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    /**
     * @inheritDoc
     */
    public function isStatic(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isPrivate(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isPublic(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDocComment(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getReadableType(): Type
    {
        return new ObjectType($this->name);
    }

    /**
     * @inheritDoc
     */
    public function getWritableType(): Type
    {
        return new ObjectType($this->name);
    }

    /**
     * @inheritDoc
     */
    public function canChangeTypeAfterAssignment(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    /**
     * @inheritDoc
     */
    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}
