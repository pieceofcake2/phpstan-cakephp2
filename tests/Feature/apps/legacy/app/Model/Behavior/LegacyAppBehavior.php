<?php
declare(strict_types=1);

class LegacyAppBehavior extends ModelBehavior
{
    /**
     * Behavior method used to check that this behavior is discovered.
     *
     * @param \Model $model Model using this behavior.
     * @return int
     */
    public function legacyAppMethod(Model $model): int
    {
        return 1;
    }
}
