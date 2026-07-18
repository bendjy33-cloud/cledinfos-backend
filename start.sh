#!/bin/sh

php artisan migrate --force

exec /start.sh