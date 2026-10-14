<?php
declare(strict_types=1);

class TaskListShell extends Shell
{
    /**
     * @var array<array-key, mixed>
     */
    public $tasks = [
        'Model',
        'Plugin.Basic' => ['setting' => true],
    ];
}
