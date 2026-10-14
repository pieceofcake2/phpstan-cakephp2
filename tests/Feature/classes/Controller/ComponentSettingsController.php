<?php
declare(strict_types=1);

class ComponentSettingsController extends Controller
{
    /**
     * @var array<array-key, mixed>
     */
    public $components = [
        'Basic' => ['setting' => true],
        'Plugin.Second',
    ];
}
