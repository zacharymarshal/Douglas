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
php composer.phar require zacharyrankin/Douglas:1.*
```

After installing, be sure to require Composer's autoload.php:

```php
require 'vendor/autoload.php';
```

### Installing via phar

Downlad the [latest phar](https://github.com/zacharyrankin/Douglas/releases) and include it in your application

```php
<?php
require 'douglas.phar';
```

### Usage

Here is an extensive example of how I use this

```php
<?php

use \Douglas\Request\Report as Report;
use \Douglas\Request\Asset as Asset;

// Add exception handling for invalid formats
$format = Report::getFormat($_GET['format']);

// I normally encrypt this and store it in the database and pass it around
$jasper_url = 'http://jasperadmin:jasperadmin@localhost:8443/jasperserver/';

// Create a new Report object that should point to a jasper report
$report = new Report(
    array(
        'jasper_url' => $jasper_url,
        // The report URL is the full resource path in Jasper
        'report_url' => '/organizations/demo/Reports/TestReport',
        // These parameters get passed automatically to your report, there 
        // are also some Jasper specific parameters you can pass as well
        'parameters' => array(
            'gender' => 'M',
            'year'   => 2014
        ),
        // This is the format you want the report to be returned as
        'format'     => $format,
    )
);

// This can be used for storing reports locally before giving them to the user
$file_name = "{$report->getPrettyUrl()}.{$format}";

// Make the request to Jasper
$report->send();

if ($report->getError()) {
    // Check to see if the request was successful or not
    // and do something nice with the error
}

if (Report::FORMAT_HTML === $format) {
    // This will request the HTML from jasper and run a callback on every
    // asset jasper returns.  This is REQUIRED because the urls that jasper
    // sends back are not web accessible and need to have the jsessionid 
    // cookie injected before being requested
    $html = $report->getHtml(
        function($asset_url, $jsessionid) use ($jasper_url) {

            // I like to request every asset from jasper, re-save them 
            // locally so I can do caching, reloading, etc and not have 
            // to rely on Jasper
            $asset = new Asset(
                array(
                    'jasper_url' => $jasper_url,
                    'jsessionid' => $jsessionid,
                    'asset_url'  => $asset_url,
                )
            );
            $asset->send();

            // I like to only save images, though Jasper will sometimes
            // send back javascript files like jquery
            if ($asset->getHeader('content-type') != 'image/png') {
                return false;
            }

            $asset_file_name = sprintf('jasper_report_asset_%s', uniqid());
            $full_asset_path = "../images/{$asset_file_name}";

            $asset_fh = fopen($full_asset_path, 'w');
            fwrite($asset_fh, $asset->getBody());
            fclose($asset_fh);
            return "/{$asset_file_name}.png";
        }
    );
} else {
    $body = $report->getBody();
    // Output the PDF or Excel here
}

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

