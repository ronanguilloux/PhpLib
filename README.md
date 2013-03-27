PhpLib
--------

Simple PHP lib, tools & helpers, all GNU AGPL'd.

Inspired by http://www.jonasjohn.de/snippets/php/

Build status
------------

[![Build Status](https://secure.travis-ci.org/ronanguilloux/PhpLib.png?branch=master)](http://travis-ci.org/ronanguilloux/PhpLib)


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

    $ php composer.phar install --dev
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

