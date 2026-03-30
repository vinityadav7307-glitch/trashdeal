#!/bin/sh
set -eu

php artisan optimize:clear
php artisan storage:link >/dev/null 2>&1 || true
php artisan migrate --force
php artisan db:seed --force
