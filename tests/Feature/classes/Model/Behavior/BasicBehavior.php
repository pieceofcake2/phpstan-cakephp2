<?php
declare(strict_types=1);

class BasicBehavior extends ModelBehavior
{
    public function behaviorMethod(Model $model, string $string): string
    {
        return 'string: ' . $string;
    }

    /**
     * Not a behavior method: static.
     */
    public static function staticMethod(Model $model): string
    {
        return 'static';
    }

    /**
     * Not a behavior method: not public.
     */
    protected function protectedMethod(Model $model): string
    {
        return 'protected';
    }

    /**
     * Not a behavior method: no parameters.
     */
    public function methodWithoutParameters(): string
    {
        return 'no parameters';
    }

    /**
     * Not a behavior method: the first parameter does not accept a Model.
     */
    public function methodWithoutModelParameter(string $string): string
    {
        return $string;
    }
}
