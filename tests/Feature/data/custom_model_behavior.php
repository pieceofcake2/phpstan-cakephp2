<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var BasicModel $model */
$result = $model->behaviorMethod('a string!');

assertType('string', $result);

// Only public, non-static methods whose first parameter accepts a Model are behavior methods.
assertType('*ERROR*', $model->staticMethod());
assertType('*ERROR*', $model->protectedMethod());
assertType('*ERROR*', $model->methodWithoutParameters());
assertType('*ERROR*', $model->methodWithoutModelParameter('a string!'));
