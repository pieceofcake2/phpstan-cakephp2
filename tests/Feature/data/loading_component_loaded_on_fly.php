<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var BasicController $controller */
$component = $controller->Components->load('Basic');

assertType('BasicComponent', $component);

// Falls back to the declared return type when the component cannot be resolved.
/** @var string $name */
assertType('Component', $controller->Components->load($name));
assertType('Component', $controller->Components->load('Unknown'));
assertType('Component', $controller->Components->load('Plain'));

// Calls without arguments must not crash the extension.
assertType('Component', $controller->Components->load());
