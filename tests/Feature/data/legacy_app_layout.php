<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

/** @var BasicModel $model */

// app/Model/Behavior
assertType('int', $model->legacyAppMethod());

// app/Plugin/*/Model/Behavior
assertType('float', $model->forumPluginMethod());

// app/Config/Schema
assertType('Model', ClassRegistry::init('LegacyArticle'));

// Only the paths of the current layout are used.
assertType('*ERROR*', $model->modernAppMethod());
assertType('*ERROR*', $model->behaviorMethod('a string!'));
assertType('object', ClassRegistry::init('ModernArticle'));
assertType('object', ClassRegistry::init('TableWithoutModel'));
