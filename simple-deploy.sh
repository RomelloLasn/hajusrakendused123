#!/bin/bash

# Simple Zone.ee deployment script for Laravel
# Usage: ./deploy.sh [username] [host]

# Configuration
SSH_USER=${1:-"virt118441"}
SSH_HOST=${2:-"tak22lasn.itmajakas.ee"}
REMOTE_DIR="/home/virt118441/domeenid/www.tak22lasn.itmajakas.ee/rakendused1"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
RELEASE_DIR="release_$TIMESTAMP"

# Display configuration
echo "=== Laravel Zone.ee Deployment ==="
echo "User: $SSH_USER"
echo "Host: $SSH_HOST"
echo "Remote directory: $REMOTE_DIR"
echo "Release: $RELEASE_DIR"

# Local build process
echo -e "\n=== Building application locally ==="
composer install --no-dev --optimize-autoloader
npm ci
npm run build
rm -rf node_modules

# Create temporary deployment directory
echo -e "\n=== Preparing deployment package ==="
mkdir -p ./$RELEASE_DIR

# Copy files to deployment directory
rsync -av --exclude='.git' \
          --exclude='.github' \
          --exclude='node_modules' \
          --exclude='tests' \
          --exclude="$RELEASE_DIR" \
          --exclude='vendor/*/*/tests' \
          ./ ./$RELEASE_DIR/

# Create Zone.ee .htaccess
cat > ./$RELEASE_DIR/.htaccess << 'HTACCESS'
Options +FollowSymLinks -Indexes
AddType application/x-httpd-php83 .php

RewriteEngine On
RewriteCond %{REQUEST_URI} !^public
RewriteRule ^(.*)$ public/$1 [L]
HTACCESS

# Create tar archive
echo -e "\n=== Creating deployment archive ==="
tar -czf deployment.tar.gz $RELEASE_DIR

# Upload to server
echo -e "\n=== Uploading to server ==="
scp deployment.tar.gz $SSH_USER@$SSH_HOST:~/

# Execute deployment commands on server
echo -e "\n=== Executing deployment on server ==="
ssh $SSH_USER@$SSH_HOST << EOF
    # Extract the deployment
    cd ~/
    tar -xzf deployment.tar.gz
    rm -f deployment.tar.gz
    
    # Create shared directories if they don't exist
    mkdir -p ~/shared/storage
    mkdir -p ~/shared/database
    touch ~/shared/database/database.sqlite
    chmod 777 ~/shared/database/database.sqlite
    
    # Backup current deployment if it exists
    if [ -L ~/rakendused1 ]; then
      current=\$(readlink -f ~/rakendused1)
      if [ -d "\$current" ]; then
        echo "Backing up current deployment"
        mv \$current ~/previous_deployment_\$(date +"%Y%m%d_%H%M%S")
      fi
    fi
    
    # Set up new deployment
    cd ~/$RELEASE_DIR
    ln -sf ~/shared/storage storage
    ln -sf ~/shared/.env .env
    ln -sf ~/shared/database/database.sqlite database/database.sqlite
    
    # Set permissions
    chmod -R 775 storage
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/framework/cache
    chmod -R 775 storage/framework/sessions
    chmod -R 775 storage/framework/views
    chmod -R 775 storage/framework/cache
    chmod -R 775 bootstrap/cache
    
    # Clear caches
    php artisan optimize:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # Run migrations
    php artisan migrate --force
    
    # Make the deployment live
    cd ~/
    ln -sfn ~/$RELEASE_DIR rakendused1
EOF

# Clean up locally
echo -e "\n=== Cleaning up ==="
rm -rf ./$RELEASE_DIR
rm -f deployment.tar.gz

echo -e "\n=== Deployment complete! ==="
echo "Your application is now live at: https://rakendused1.romello.zone/"
