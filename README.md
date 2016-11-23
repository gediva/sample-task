skelbimai
=========

Requirements
============
* Bower package manager must be installed.
* Composer pacakge manager must be installed.

Installation
============

Make sure to make 'var/' directory writable.

1. Run `composer install`, to install required dependencies.
2. Run `bower install`, to install required css and javascript files.
3. Run `php bin/console doctrine:database:create`, to create database for ads.
4. Run `php bin/console doctrine:schema:update --force`, to add required tables for database.
5. Run `php bin/console server:run`, to start the app on localhost port 8000. Reach it by accessing `http://localhost:8000` or 'http://127.0.0.1:8000'.
