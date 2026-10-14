<?php
declare(strict_types=1);

use function PHPStan\Testing\assertType;

$class = ClassRegistry::init('BasicModel');
assertType('BasicModel', $class);

// Without $strict, init() creates an AppModel for a class which does not exist.
$notClass = ClassRegistry::init('NotAClass');
assertType('object', $notClass);

$modelWithoutClass = ClassRegistry::init('TableWithoutModel');
assertType('Model', $modelWithoutClass);

$class = ClassRegistry::init(BasicModel::class);
assertType('BasicModel', $class);

$var = 'BasicModel';

assertType('BasicModel', ClassRegistry::init($var));

assertType('bool|object', ClassRegistry::init([123]));

/** @var string $className */
assertType('object', ClassRegistry::init($className));

// Calls without arguments must not crash the extension.
assertType('bool|object', ClassRegistry::init());

// Models in plugin syntax.
assertType('BasicModel', ClassRegistry::init('Plugin.BasicModel'));
assertType('Model', ClassRegistry::init('Plugin.TableWithoutModel'));
assertType('object', ClassRegistry::init('Plugin.NotAClass'));

// Models given as an array with a class key.
assertType('BasicModel', ClassRegistry::init(['class' => 'BasicModel', 'alias' => 'Alias']));
assertType('BasicModel', ClassRegistry::init(['class' => 'Plugin.BasicModel', 'alias' => 'Alias']));
assertType('Model', ClassRegistry::init(['class' => 'Plugin.TableWithoutModel']));
// Without a class key, init() creates an AppModel.
assertType('object', ClassRegistry::init(['alias' => 'BasicModel']));

// Class names which are not constant strings.
/** @var non-empty-string $nonEmptyClassName */
assertType('object', ClassRegistry::init($nonEmptyClassName));
assertType('object', ClassRegistry::init(['class' => $className, 'alias' => 'Alias']));
assertType('object', ClassRegistry::init(['class' => $className, 'alias' => $className, 'table' => false]));
/** @var class-string<BasicModel> $basicModelClass */
assertType('BasicModel', ClassRegistry::init($basicModelClass));
/** @var class-string $anyClass */
assertType('object', ClassRegistry::init($anyClass));

/** @var 'BasicModel'|'SecondModel' $knownClasses */
assertType('BasicModel|SecondModel', ClassRegistry::init($knownClasses));
/** @var 'BasicModel'|'NotAClass' $partlyKnownClasses */
assertType('object', ClassRegistry::init($partlyKnownClasses));

// Without $strict (or with a false one), init() never returns false.
assertType('object', ClassRegistry::init($className, false));
assertType('BasicModel', ClassRegistry::init('BasicModel', false));

// With $strict, init() returns false when the class does not exist or is not a model.
assertType('BasicModel', ClassRegistry::init('BasicModel', true));
assertType('BasicModel', ClassRegistry::init(['class' => 'Plugin.BasicModel'], true));
assertType('object|false', ClassRegistry::init('NotAClass', true));
assertType('object|false', ClassRegistry::init('TableWithoutModel', true));
assertType('object|false', ClassRegistry::init('stdClass', true));
assertType('object|false', ClassRegistry::init($className, true));
assertType('BasicModel', ClassRegistry::init($basicModelClass, true));
assertType('stdClass', ClassRegistry::init('stdClass'));

// With a $strict which may be true, both are possible.
/** @var bool $strict */
assertType('object|false', ClassRegistry::init($className, $strict));
assertType('BasicModel', ClassRegistry::init('BasicModel', $strict));
assertType('object|false', ClassRegistry::init('TableWithoutModel', $strict));

// Values other than arrays are wrapped in a configuration, like strings.
/** @var int|string $key */
assertType('object', ClassRegistry::init($key));
assertType('object', ClassRegistry::init(['class' => $key, 'alias' => 'Alias']));
assertType('object|false', ClassRegistry::init(['class' => $key], true));
/** @var array<string, string> $config */
assertType('object', ClassRegistry::init($config));
/** @var array{class: int} $intConfig */
assertType('object', ClassRegistry::init($intConfig));

// Lists of configurations, and values which may be such lists, keep the declared type.
assertType('bool|object', ClassRegistry::init([['class' => 'BasicModel'], ['class' => 'SecondModel']]));
/** @var array<int, array{class: string}> $configs */
assertType('bool|object', ClassRegistry::init($configs));
/** @var array<mixed> $anyArray */
assertType('bool|object', ClassRegistry::init($anyArray));
/** @var string|array<mixed> $stringOrArray */
assertType('bool|object', ClassRegistry::init($stringOrArray));
/** @var mixed $mixed */
assertType('bool|object', ClassRegistry::init($mixed));
