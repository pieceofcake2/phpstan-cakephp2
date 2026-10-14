<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var BasicModel $model */

// src/Model/Behavior
assertType('int', $model->modernAppMethod());

// plugins/*/Model/Behavior
assertType('float', $model->blogPluginMethod());

// config/Schema
assertType('Model', ClassRegistry::init('ModernArticle'));

// Only the paths of the current layout are used.
assertType('*ERROR*', $model->legacyAppMethod());
assertType('*ERROR*', $model->behaviorMethod('a string!'));
assertType('object', ClassRegistry::init('LegacyArticle'));
assertType('object', ClassRegistry::init('TableWithoutModel'));
