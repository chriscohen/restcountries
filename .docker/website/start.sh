#!/usr/bin/env bash
echo "Running composer install..."
cd /var/www
composer install

echo "Starting Apache2..."
apache2ctl -DFOREGROUND
