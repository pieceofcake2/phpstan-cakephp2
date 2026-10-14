<?php
declare(strict_types=1);

class BasicBehavior extends ModelBehavior
{
    public function behaviorMethod(Model $model, string $string): string
    {
        return 'string: ' . $string;
    }
}
