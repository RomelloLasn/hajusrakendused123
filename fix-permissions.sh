#!/bin/bash

# Fix storage directory permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create required directories if they don't exist
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# Set proper permissions for directories
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/views
chmod -R 775 storage/framework/cache
chmod -R 775 storage/logs

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Ensure storage link exists
php artisan storage:link

echo "Permissions and directories fixed!"
