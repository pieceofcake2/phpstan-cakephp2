<?php
declare(strict_types=1);

class ModernAppBehavior extends ModelBehavior
{
    /**
     * Behavior method used to check that this behavior is discovered.
     *
     * @param \Model $model Model using this behavior.
     * @return int
     */
    public function modernAppMethod(Model $model): int
    {
        return 1;
    }
}
