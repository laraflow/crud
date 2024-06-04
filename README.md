# API CRUD

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laraflow/api-crud.svg?style=flat-square)](https://packagist.org/packages/laraflow/api-crud)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laraflow/api-crud/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laraflow/api-crud/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/laraflow/api-crud/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/laraflow/api-crud/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laraflow/api-crud.svg?style=flat-square)](https://packagist.org/packages/laraflow/api-crud)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require laraflow/api-crud
```

After that run this command to configure project.

```bash
php artisan laraflow:install
```

## Usage

To create a complete API CRUD stub files use this command

```bash
php artisan laraflow:make-crud [ResourceName]
```

Optionally, If you want to create inside subdirectory.

```bash
php artisan laraflow:make-crud [Directory1/Directorry2/ResourceName]
```

Note: Root Namespace will to added from configuration.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mohammad Hafijul Islam](https://github.com/hafijul233)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
