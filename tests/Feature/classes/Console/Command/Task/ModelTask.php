<?php
declare(strict_types=1);

/**
 * A task named like the core Model class, as in the bake plugin.
 */
class ModelTask extends Shell
{
    /**
     * @return array<string>
     */
    public function listAll(): array
    {
        return [];
    }
}
