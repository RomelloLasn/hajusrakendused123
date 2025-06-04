#!/bin/bash

# Zone.ee Deployment Validation Script
echo "=== Zone.ee Deployment Validation ==="

# Configuration
SSH_USER="virt118441"
SSH_HOST="tak22lasn.itmajakas.ee"
DEPLOY_PATH="~/domeenid/www.tak22lasn.itmajakas.ee/rakendused1"
SHARED_PATH="~/domeenid/www.tak22lasn.itmajakas.ee/shared"

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color
YELLOW='\033[1;33m'

# Function to check step
check_step() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ $1${NC}"
    else
        echo -e "${RED}✗ $1${NC}"
        if [ ! -z "$2" ]; then
            echo -e "${YELLOW}Suggestion: $2${NC}"
        fi
    fi
}

echo -e "\n=== Testing SSH Connection ==="
ssh -q $SSH_USER@$SSH_HOST exit
check_step "SSH Connection" "Make sure your SSH key is properly set up and you can connect to the server"

echo -e "\n=== Checking Directory Structure ==="
ssh $SSH_USER@$SSH_HOST "
    echo 'Checking deployment path...'
    if [ -L $DEPLOY_PATH ]; then
        echo 'Deployment symlink exists'
        ls -la $DEPLOY_PATH
    else
        echo 'Deployment path does not exist as a symlink'
    fi
    
    echo -e '\nChecking shared directories...'
    for dir in storage database; do
        if [ -d $SHARED_PATH/\$dir ]; then
            echo \"✓ \$dir directory exists\"
            ls -la $SHARED_PATH/\$dir
        else
            echo \"✗ \$dir directory missing\"
        fi
    done
    
    echo -e '\nChecking .env file...'
    if [ -f $SHARED_PATH/.env ]; then
        echo '✓ .env file exists'
        grep -v 'DB_PASSWORD\|APP_KEY' $SHARED_PATH/.env || true
    else
        echo '✗ .env file missing'
    fi
    
    echo -e '\nChecking PHP version...'
    php -v
    
    echo -e '\nChecking storage permissions...'
    ls -la $DEPLOY_PATH/storage 2>/dev/null || echo 'Storage directory not accessible'
"

echo -e "\n=== Testing Web Access ==="
curl -I "https://rakendused1.tak22lasn.itmajakas.ee" 2>/dev/null | head -n 1
check_step "Web Access" "Make sure your domain is properly configured in Zone.ee panel"

echo -e "\n=== Validation Complete ==="
echo "If any checks failed, please fix them before deploying"
