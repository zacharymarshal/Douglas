Douglas
=======

[![Build Status](https://travis-ci.org/zacharyrankin/Douglas.png?branch=master)](https://travis-ci.org/zacharyrankin/Douglas)

Douglas PHP library for running Jasper Reports using their report REST API (v2)

### Installing via Composer

I recommend you install Douglas using [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Add Douglas as a dependency
php composer.phar require zacharyrankin/Douglas:~1
```

After installing, be sure to require Composer's autoload.php:

```php
require 'vendor/autoload.php';
```

### Installing via phar

Clone the repository and run:

```bash
php create-phar.php
```

Move from `build/douglas.phar` to your application and include it:

```php
<?php
require 'douglas.phar';
```

### Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

#### Unit Testing

Install PHPUnit using composer `php composer.phar install --dev` and then you can run the tests using `vendor/bin/phpunit`

#### Coding Standards

Stick to [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

I recommend running [PHP Code Sniffer with PSR-2 Standards](https://github.com/klaussilveira/phpcs-psr) by running `phpcs --standard=PSR2 ./`

