# API CRUD

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laraflow/crud.svg?style=flat-square)](https://packagist.org/packages/laraflow/crud)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laraflow/crud/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laraflow/crud/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/laraflow/crud/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/laraflow/crud/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laraflow/crud.svg?style=flat-square)](https://packagist.org/packages/laraflow/crud)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require laraflow/crud
```

After that run this command to configure project.

```bash
php artisan laraflow:install
```

## Usage

To create a complete API CRUD stub file use this command

```bash
php artisan laraflow:make-crud ResourceName
```

If you want to add fields to migration and request validation then

```bash
php artisan laraflow:make-crud ResourceName --fields=name,email,phone,image
```

Optionally, If you want to create an inside subdirectory.

```bash
php artisan laraflow:make-crud Directory1/Directorry2/ResourceName
```

Note: Root Namespace will be added from the configuration.

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
