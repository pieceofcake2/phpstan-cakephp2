<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var PropertyTagController $controller */

// @property tags take precedence over the models and components resolved by the extension.
assertType('SecondModel', $controller->BasicModel);
assertType('SecondComponent', $controller->Basic);
