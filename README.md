PhpLib
--------

Simple PHP lib, tools & helpers, all GNU AGPL'd.

Inspired by http://www.jonasjohn.de/snippets/php/

[![Build Status](https://secure.travis-ci.org/ronanguilloux/PhpLib.png?branch=master)](http://travis-ci.org/ronanguilloux/PhpLib)


Build status
------------

[![Build Status](https://secure.travis-ci.org/ronanguilloux/PhpLib.png?branch=master)](http://travis-ci.org/ronanguilloux/PhpLib)


Usage
-----

    // Will this financial transaction succeed ?
    $isSwiftBic = SwiftBic::validate( 'CEDELULLXXX' );

    // Will my letter reach the Labrador islands ?
    $isCanadianZipCode = ZipCode::validate( 'A0A 1A0', 'Canada');

    // American Express, anyone ?
    $isAmericanExpress = CreditCard::validate( '12345679123456' );


Installing via GitHub
---------------------

    $ git clone git@github.com:ronanguilloux/PhpLib.git

Autoloading is PSR-0 friendly.

Installing via [Packagist](https://packagist.org/packages/ronanguilloux/phplib) & [Composer](http://getcomposer.org/doc/00-intro.md)
-----------------------------------

Create a composer.json file:

    {
        "require": {"ronanguilloux/phplib": "dev-master"}
    }


Grab composer:

    $ curl -s http://getcomposer.org/installer | php

Run install (will build the autoload):

    $ php composer.phar install


Testing
-------

    $ phpunit --coverage-text


License Information
-------------------

* GNU GPL v3
* You can find a copy of this software here: https://github.com/ronanguilloux/PhpLib


Contributing Code
-----------------

The issue queue can be found at: https://github.com/ronanguilloux/PhpLib/issues
All contributors will be fully credited. Just sign up for a github account, create a fork and hack away at the codebase.
Submit patches to: ronan.guilloux@gmail.com
Even one-off contributors will be fully credited (& probably blessed through three generations).

