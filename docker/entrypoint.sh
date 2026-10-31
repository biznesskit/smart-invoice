#!/bin/bash
set -e

# Run Laravel migrations
php artisan migrate --database=landlord --path=database/migrations/landlord --force --isolated

# Start Supervisor (which starts PHP-FPM & Nginx)
 
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
