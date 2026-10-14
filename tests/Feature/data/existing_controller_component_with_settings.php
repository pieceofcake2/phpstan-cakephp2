<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var ComponentSettingsController $controller */

// Component defined with settings: 'Basic' => [...]
assertType('BasicComponent', $controller->Basic);

// Component defined in plugin syntax: 'Plugin.Second'
assertType('SecondComponent', $controller->Second);
