# PHPStan-CakePHP2

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/phpstan-cakephp2?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/phpstan-cakephp2?label=Packagist)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/phpstan-cakephp2/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![PHPStan](https://img.shields.io/packagist/dependency-v/pieceofcake2/phpstan-cakephp2/phpstan/phpstan?label=PHPStan&labelColor=%23495EA3&color=%23FFFFFF)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/phpstan-cakephp2/CI.yml?label=CI)](https://github.com/pieceofcake2/phpstan-cakephp2/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/phpstan-cakephp2?label=Coverage)](https://codecov.io/gh/pieceofcake2/phpstan-cakephp2)

**This is forked for CakePHP2.**

PHPStan extensions to help test CakePHP 2 projects with PHPStan

## Requirements

* PHP 8.0+
* phpstan/phpstan 2.x
* pieceofcake2/cakephp 2.x

## Installation

Installation is best done through composer:
```shell
composer require --dev pieceofcake2/phpstan-cakephp2
```

You will need to make sure the extension is included in your phpstan config:
```yaml
# phpstan.neon
includes:
  - vendor/pieceofcake2/phpstan-cakephp2/extension.neon
```

### Behavior and schema locations

Behaviors and schema files are discovered with glob patterns, relative to the directory PHPStan is run from
(normally your project root). The defaults in `extension.neon` cover:

* the core behaviors of pieceofcake2/cakephp (`vendor/pieceofcake2/cakephp/src/Cake/Model/Behavior`, and the
  `lib/Cake` and `src/Cake` paths used when analysing older releases or CakePHP itself)
* the modern pieceofcake2/app layout: `src/Model/Behavior`, `plugins/*/Model/Behavior` and `config/Schema`
* the legacy CakePHP 2 layout: `app/Model/Behavior`, `app/Plugin/*/Model/Behavior` and `app/Config/Schema`

If you have behavior classes or schema files in other locations (perhaps in a vendor directory), add those
locations to your configuration. They are merged with the defaults. For example:
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

* Treat behavior methods as extra methods on all models (`$model->behaviorMethod()`)
* Treat controller, model and shell properties named after model classes as instances of those classes
  (`$controller->Model`)
* Treat controller and component properties named after components listed in `$components` as instances of those
  classes (`$controller->Component`, `$component->Component`)
* Treat shell properties named after task classes as instances of those classes (`$shell->Task`)
* Treat `ClassRegistry::init($className)` as returning an instance of `$className` where possible, or `Model`
  when only a table for it is defined in a schema file
* Treat `$this->Components->load('Name')` as returning an instance of `NameComponent`

## Development

```shell
composer install

# Run the tests
vendor/bin/phpunit

# Run static analysis
vendor/bin/phpstan

# Check the coding standard (cakephp/cakephp-codesniffer), and fix what can be fixed automatically
vendor/bin/phpcs
vendor/bin/phpcbf
```

The same checks are available as Composer scripts: `composer phpunit`, `composer phpstan`, `composer cs-check`,
`composer cs-fix`, and `composer test` to run the tests, static analysis and coding standard check in one go.
