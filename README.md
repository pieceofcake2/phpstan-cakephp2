# PHPStan-CakePHP2

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/phpstan-cakephp2?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/phpstan-cakephp2?label=Packagist)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/phpstan-cakephp2/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![Tests](https://img.shields.io/github/actions/workflow/status/pieceofcake2/phpstan-cakephp2/CI.yml?label=CI)](https://github.com/pieceofcake2/phpstan-cakephp2/actions/workflows/tests.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/phpstan-cakephp2?label=Coverage)](https://codecov.io/gh/pieceofcake2/phpstan-cakephp2)

PHPStan extensions to help test CakePHP 2 projects with PHPStan

## Requirements

* PHP 8.0+
* phpstan/phpstan 1.9+
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

If you have behavior classes in odd locations (perhaps in a vendor directory) you will need to add those locations to
your configuration. For example:
```yaml
# phpstan.neon
parameters:
  ModelBehaviorsExtension:
    behaviorPaths:
      - vendor/my-vendor/my-plugin/src/Model/Behavior/*.php
```
See `extension.neon` for the default list of behavior locations.

## Features

The following features are added to PHPStan:

* Treat behavior methods as extra methods on all models (`$model->behaviorMethod()`)
* Treat controller properties named after model classes as instances of those classes (`$controller->Model`)
* Treat controller properties named after component classes as instances of those classes (`$controller->Component`)
* Treat component properties names after component classes as instances of those classes (`$component->Component`)
* Treat `ClassRegistry::init($className)` as returning an instance of `$className` where possible
