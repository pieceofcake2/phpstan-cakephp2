<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

// Core behaviors are discovered through vendor/pieceofcake2/cakephp/src/Cake/Model/Behavior.

/** @var BasicModel $model */
$result = $model->bindTranslation([]);

assertType('bool', $result);

// AclBehavior
assertType('array', $model->node());

// ContainableBehavior
assertType('null', $model->contain());

// TranslateBehavior
assertType('bool', $model->unbindTranslation());

// TreeBehavior
assertType('int', $model->childCount());
assertType('array', $model->generateTreeList());
