<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var TaskListShell $shell */

// A task listed in $tasks takes precedence over the model of the same name.
assertType('ModelTask', $shell->Model);
assertType('array<string>', $shell->Model->listAll());

// Tasks listed with settings and in plugin syntax.
assertType('BasicTask', $shell->Basic);

// Models which are not listed in $tasks are still resolved.
assertType('BasicModel', $shell->BasicModel);

/** @var TaskListChildShell $childShell */

// Tasks listed in $tasks of a parent class.
assertType('ModelTask', $childShell->Model);
assertType('BasicModel', $childShell->BasicModel);

/** @var BasicShell $basicShell */

// Without a $tasks entry, the property is still resolved to the model.
assertType('Model', $basicShell->Model);
