![Coverage Status](https://coveralls.io/repos/github/Ferror/openapi-coverage/badge.svg?branch=master)

# Open API Coverage Calculator Symfony Bundle

The Open API Coverage Calculator Symfony Bundle enables you to track another system metric - The API Documentation Coverage.
The library extracts your application API endpoints via Symfony Routing and checks them against [NelmioAPIDocBundle](https://github.com/nelmio/NelmioApiDocBundle),
the most popular library for Open API Specification in the [Symfony](https://github.com/symfony) ecosystem.

## Installation

```bash
composer require --dev ferror/openapi-coverage
```

```php
// bundles.php

return [
    Ferror\OpenapiCoverage\Symfony\Bundle::class => ['dev' => true, 'test' => true],
];
```

## Usage

```bash
php bin/console ferror:check-openapi-coverage
```

> You can also specify the --threshold or (--t) option to define coverage level below which the command will fail.

```bash
php bin/console ferror:check-openapi-coverage --threshold 0.70
```

## Example Result

```
Open API coverage: 75%
+- Missing documentation -+
| path          | method  |
+---------------+---------+
| /products/:id | get     |
+---------------+---------+
```
