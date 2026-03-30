#!/bin/sh
set -eu

php artisan optimize:clear
php artisan storage:link >/dev/null 2>&1 || true
php artisan migrate --force
php artisan db:seed --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
