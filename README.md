# PHPStan-CakePHP2

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/phpstan-cakephp2?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/phpstan-cakephp2?label=Packagist)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/phpstan-cakephp2/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![PHPStan](https://img.shields.io/packagist/dependency-v/pieceofcake2/phpstan-cakephp2/phpstan/phpstan?label=PHPStan&labelColor=%23495EA3&color=%23FFFFFF)](https://packagist.org/packages/pieceofcake2/phpstan-cakephp2)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/phpstan-cakephp2/CI.yml?label=CI)](https://github.com/pieceofcake2/phpstan-cakephp2/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/phpstan-cakephp2?label=Coverage)](https://codecov.io/gh/pieceofcake2/phpstan-cakephp2)

A [PHPStan](https://phpstan.org/) extension for CakePHP 2 applications and plugins running on
[pieceofcake2/cakephp](https://github.com/pieceofcake2/cakephp). It teaches PHPStan the magic
of CakePHP 2: models, components and tasks loaded as properties, behavior methods called on
models, and `ClassRegistry::init()`. This is a fork of
[sidz/phpstan-cakephp2](https://github.com/sidz/phpstan-cakephp2).

## Documentation

- Website: https://pieceofcake2.github.io/phpstan-cakephp2/
- Markdown: [docs/README.md](docs/README.md) (for AI tools and offline reading)

The Markdown documentation is also included in the Composer package, at `vendor/pieceofcake2/phpstan-cakephp2/docs/README.md`. Install the package with `--dev` if you only need it for development.

## Requirements

- PHP 8.0+
- phpstan/phpstan 2.x
- pieceofcake2/cakephp 2.x

## Installation

```bash
composer require --dev pieceofcake2/phpstan-cakephp2
```

Then include the extension in your PHPStan configuration:

```yaml
# phpstan.neon
includes:
  - vendor/pieceofcake2/phpstan-cakephp2/extension.neon
```

## Contributing

See the [contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

## Origin

This package is a maintained fork of
[sidz/phpstan-cakephp2](https://github.com/sidz/phpstan-cakephp2) (`sidz/phpstan-cakephp2` on
Packagist, last upstream version 0.2.0), originally by Oleg Zhulnev. It is maintained for CakePHP 2
on modern PHP by [pieceofcake2](https://github.com/pieceofcake2). Changes from the original are
listed in the
[commit history since the fork](https://github.com/pieceofcake2/phpstan-cakephp2/compare/21673aa...main).

## License

Licensed under the MIT License. See [LICENSE](LICENSE).
