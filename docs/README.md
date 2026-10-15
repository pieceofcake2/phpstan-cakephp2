# PHPStan-CakePHP2 Documentation

pieceofcake2/phpstan-cakephp2 is a [PHPStan](https://phpstan.org/) extension for CakePHP 2
applications and plugins running on [pieceofcake2/cakephp](https://github.com/pieceofcake2/cakephp).
It teaches PHPStan the magic of CakePHP 2: models, components and tasks loaded as
properties, behavior methods called on models, and `ClassRegistry::init()`. It is a fork of
[sidz/phpstan-cakephp2](https://github.com/sidz/phpstan-cakephp2).

- [Installation](#installation)
- [Behavior and Schema Locations](#behavior-and-schema-locations)
- [Features](#features)
- [Stubs](#stubs)
- [Development](#development)

## Installation

Install the extension with [Composer](https://getcomposer.org/) as a development dependency:

```bash
composer require --dev pieceofcake2/phpstan-cakephp2
```

Include the extension in your PHPStan configuration:

```yaml
# phpstan.neon
includes:
  - vendor/pieceofcake2/phpstan-cakephp2/extension.neon
```

If you use [phpstan/extension-installer](https://github.com/phpstan/extension-installer),
the extension is included automatically and this is not necessary.

The [pieceofcake2/app](https://github.com/pieceofcake2/app) skeleton is already set up with
the extension.

## Behavior and Schema Locations

Behaviors and schema files are discovered with glob patterns, relative to the directory
PHPStan is run from (normally your project root). The defaults in `extension.neon` cover:

- the core behaviors of pieceofcake2/cakephp (`vendor/pieceofcake2/cakephp/src/Cake/Model/Behavior`,
  and the `lib/Cake` and `src/Cake` paths used when analysing older releases or CakePHP itself)
- the modern pieceofcake2/app layout: `src/Model/Behavior`, `plugins/*/Model/Behavior` and
  `config/Schema`
- the legacy CakePHP 2 layout: `app/Model/Behavior`, `app/Plugin/*/Model/Behavior` and
  `app/Config/Schema`

If you have behavior classes or schema files in other locations (perhaps in a vendor
directory), add those locations to your configuration. They are merged with the defaults.
For example:

```yaml
# phpstan.neon
parameters:
  ModelBehaviorsExtension:
    behaviorPaths:
      - vendor/my-vendor/my-plugin/src/Model/Behavior/*.php
  SchemaService:
    schemaPaths:
      - vendor/my-vendor/my-plugin/config/Schema/*.php
```

## Features

The following features are added to PHPStan:

- Treat behavior methods as extra methods on all models (`$model->behaviorMethod()`).
- Treat controller, model and shell properties named after model classes as instances of
  those classes (`$controller->Model`).
- Treat controller and component properties named after components listed in `$components`
  as instances of those classes (`$controller->Component`, `$component->Component`).
- Treat shell properties named after task classes as instances of those classes
  (`$shell->Task`). A task listed in the `$tasks` of a shell or task takes precedence over a
  model of the same name (e.g. the `Model` task of bake).
- Leave properties declared with `@property` tags to PHPStan, so that the tags take
  precedence over the properties added by this extension.
- Treat `ClassRegistry::init($className)` as returning an instance of `$className` where
  possible, or `Model` when only a table for it is defined in a schema file. The class name
  can be in plugin syntax (`'Plugin.Model'`), or given as an array with a `class` key
  (`['class' => 'Plugin.Model', 'alias' => 'Alias']`). Other class names (e.g. a `string`
  variable) resolve to `object`, as `init()` never returns false for a single class without
  `$strict`: it creates an `AppModel` when the class does not exist. With `$strict = true`,
  `init()` returns false when the class does not exist or is not a model, so only model classes
  resolve to their instance, and anything else resolves to `object|false`. Lists of configurations, and values which may be
  such lists (e.g. `mixed`), keep the declared `bool|object`.
- Treat `$this->Components->load('Name')` as returning an instance of `NameComponent`.

For example, PHPStan knows the types of all the properties and calls below:

```php
class PostsController extends AppController
{
    public $components = ['Paginator'];

    public function index()
    {
        // Post (a model class), PaginatorComponent (listed in $components)
        $this->set('posts', $this->Paginator->paginate($this->Post));

        // TreeBehavior::generateTreeList(), called on the model
        $this->set('categories', $this->Post->Category->generateTreeList());

        // Tag
        $Tag = ClassRegistry::init('Tag');
        $this->set('tags', $Tag->find('list'));
    }
}
```

## Stubs

The extension also ships PHPStan stubs with more precise types for `Model`, `Router` and
`ClassRegistry`, and declarations of the global translation functions (`__()`, `__n()`, ...)
of `basics.php`. They are loaded by `extension.neon`.

## Development

```bash
composer install

# Run the tests
vendor/bin/phpunit

# Run static analysis
vendor/bin/phpstan

# Check the coding standard (cakephp/cakephp-codesniffer), and fix what can be fixed automatically
vendor/bin/phpcs
vendor/bin/phpcbf
```

The same checks are available as Composer scripts: `composer phpunit`, `composer phpstan`,
`composer cs-check`, `composer cs-fix`, and `composer test` to run the tests, static
analysis and coding standard check in one go.

## Origin

This package is a maintained fork of
[sidz/phpstan-cakephp2](https://github.com/sidz/phpstan-cakephp2) (`sidz/phpstan-cakephp2` on
Packagist, last upstream version 0.2.0), originally by Oleg Zhulnev. It is maintained for CakePHP 2
on modern PHP by [pieceofcake2](https://github.com/pieceofcake2). Changes from the original are
listed in the
[commit history since the fork](https://github.com/pieceofcake2/phpstan-cakephp2/compare/21673aa...main).
