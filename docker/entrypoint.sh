#!/bin/bash
set -e
 
echo "Starting Application..."
 
# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi
 
# Clear file-based caches (safe before DB exists)
php artisan config:clear
php artisan route:clear
php artisan view:clear
 
# Run migrations
php artisan migrate --force --no-interaction
 
# Clear database cache (safe after migrations)
php artisan cache:clear || true
 
# Seed essential data
#php artisan db:seed --class=RolesAndPermissionsSeeder --force --no-interaction
#php artisan db:seed --class=AdminUserSeeder --force --no-interaction
 
# Cache for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
 
# Storage and permissions
php artisan storage:link 2>/dev/null || true
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
 
echo "Setup complete. Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf