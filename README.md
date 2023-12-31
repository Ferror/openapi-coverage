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

## usage

```bash
php bin/console ferror:check-openapi-coverage
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
