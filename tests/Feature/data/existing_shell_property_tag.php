<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var PropertyTagShell $shell */

// @property tags take precedence over the models and tasks resolved by the extension.
assertType('ModelTask', $shell->Model);
assertType('SecondModel', $shell->BasicModel);
assertType('BasicTask', $shell->Second);
