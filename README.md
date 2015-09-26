# lfj-configuration-builder

A PHP library to merge configuration arrays.

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
composer require lorenzoferrarajr/lfj-configuration-builder
```

## Usage

Instantiate a `ConfigurationBuilder` object, add configurations, call the `build` method to get the merged array. `Zend\Stdlib\ArrayUtils::merge` is used for merging.

### Adding configurations

The `ConfigurationBuilder` provides various methods that can be used to add configurations:

- `withFile`: to add a single php file returning an array
- `withFiles`: to add multiple files
- `withDirectory`: to add files located inside a directory
- `withArray`: to add configuration from array

### Exceptions

To catch exceptions the `build` method can be called inside a `try` block. Available exceptions are:

- `FileNotExistsException`
- `FileNotReadableException`
- `NotArrayException`: if a configuration file does not return an array
- `NotFileException`

## Examples

For the examples two configuration files are be used: `mail.global.php` and `mail.local.php`

```php
<?php
// mail.global.php

return array(
    'mail' => array(
        'host' => 'localhost',
        'port' => 25
    )
);
```

```php
<?php
// mail.local.php

return array(
    'mail' => array(
        'host' => '192.168.1.1',
    )
);

```

### Building configuration from single files

In this example two files are passed to the `ConfigurationBuilder` object: first the `mail.global.php` file and then `mail.local.php`. The result will be an array containing the `port` of the first and the `host` of the second.

```php
$cb = new \Lfj\ConfigurationBuilder\ConfigurationBuilder();

$cb->withFile(__DIR__.'/config/mail.global.php');
$cb->withFile(__DIR__.'/config/mail.local.php');

$config = $cb->build();
```

### Building configuration from multiple files at once

This example is the same as the previous one but files are passed all at once as an array.

```php
$cb = new \Lfj\ConfigurationBuilder\ConfigurationBuilder();

$cb->withFiles(array(
    __DIR__.'/config/mail.global.php',
    __DIR__.'/config/mail.local.php',
));

$config = $cb->build();
```

### Building configuration from a directory

In this example configuration files are loaded from a directory. The pattern used for file matching is the same as `global($pattern, GLOB_BRACE)`, so first `mail.global.php` and then `mail.local.php`.

```php
$cb = new \Lfj\ConfigurationBuilder\ConfigurationBuilder();

$cb->withDirectory(__DIR__.'/config/*.{global,local}.php');

$config = $cb->build();
```

More information on patterns can be found in the [glob](http://php.net/glob) documentation.

### Building configuration from a directory and array

This example is the same as the previous but before calling the `build` method a new configuration is provided by passing an array.

```php
$cb = new \Lfj\ConfigurationBuilder\ConfigurationBuilder();

$cb->withDirectory(__DIR__.'/config/*.{global,local}.php');
$cb->withArray(array(
    'mail' => array(
        'host' => 'other'
    )
));

$config = $cb->build();
```
