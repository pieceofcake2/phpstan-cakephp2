<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

$class = ClassRegistry::init('BasicModel');
assertType('BasicModel', $class);

$notClass = ClassRegistry::init('NotAClass');
assertType('bool|object', $notClass);

$modelWithoutClass = ClassRegistry::init('TableWithoutModel');
assertType('Model', $modelWithoutClass);

$class = ClassRegistry::init(BasicModel::class);
assertType('BasicModel', $class);

$var = 'BasicModel';

assertType('BasicModel', ClassRegistry::init($var));

assertType('bool|object', ClassRegistry::init([123]));

/** @var string $className */
assertType('bool|object', ClassRegistry::init($className));

// Calls without arguments must not crash the extension.
assertType('bool|object', ClassRegistry::init());

// Models in plugin syntax.
assertType('BasicModel', ClassRegistry::init('Plugin.BasicModel'));
assertType('Model', ClassRegistry::init('Plugin.TableWithoutModel'));
assertType('bool|object', ClassRegistry::init('Plugin.NotAClass'));

// Models given as an array with a class key.
assertType('BasicModel', ClassRegistry::init(['class' => 'BasicModel', 'alias' => 'Alias']));
assertType('BasicModel', ClassRegistry::init(['class' => 'Plugin.BasicModel', 'alias' => 'Alias']));
assertType('Model', ClassRegistry::init(['class' => 'Plugin.TableWithoutModel']));
assertType('bool|object', ClassRegistry::init(['alias' => 'BasicModel']));
