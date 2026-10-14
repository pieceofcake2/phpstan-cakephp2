<?php
declare(strict_types=1);

class BlogPluginBehavior extends ModelBehavior
{
    /**
     * Behavior method used to check that this behavior is discovered.
     *
     * @param \Model $model Model using this behavior.
     * @return float
     */
    public function blogPluginMethod(Model $model): float
    {
        return 1.0;
    }
}
