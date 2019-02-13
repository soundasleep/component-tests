component-tests [![Build Status](https://travis-ci.org/soundasleep/component-tests.svg?branch=master)](https://travis-ci.org/soundasleep/component-tests)
===============

Common Composer and PHP component lint and validation tests.

## Tests

1. Check that all `.json` files are valid JSON (using `json_decode`)
1. Check that all `.php` files are valid PHP (using `php -l`)
1. Check that all `composer.json` files are valid according to the [Composer JSON-schema](https://getcomposer.org/doc/04-schema.md#json-schema)
1. Check that all PHP files that use `require()` etc. use `__DIR__` in the path
1. Check that all PHP files that use `require()` etc. refer to files that actually exist

## Using

First include `component-tests` as a requirement in your project `composer.json`,
and run `composer update` to install it into your project:

```json
{
  "require": {
    "soundasleep/component-tests": "~0.2"
  }
}
```

Now create an instance of `\ComponentTests\ComponentTest` to define which paths
to search (and optionally exclude):

```php
class MyComponentTest extends \ComponentTests\ComponentTest {

  function getRoots() {
    return array(__DIR__ . "/..");
  }

  /**
   * Optional: exclude certain paths
   */
  function getExcludes() {
    return array("/resources/", "/vendor/");
  }
}
```

You can now run this test through your normal `phpunit`.

## Tests

This component is tested itself; install the composer requirements with `composer install` and run `vendor/bin/phpunit`.
