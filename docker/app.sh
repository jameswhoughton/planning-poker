#! /bin/bash

php artisan optimize
php artisan migrate

php artisan serve --host=0.0.0.0
