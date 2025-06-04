#!/bin/bash

# Test Zone.ee Connectivity Script
echo "=== Zone.ee Server Connection Test ==="

# Configuration
SERVER_HOST="tak22lasn.itmajakas.ee"
SERVER_USER="virt118441"
DEPLOY_PATH="~/domeenid/www.tak22lasn.itmajakas.ee/rakendused1"

# 1. DNS Resolution Test
echo -e "\n=== DNS Resolution Test ==="
echo "Testing DNS resolution for $SERVER_HOST..."
host $SERVER_HOST
if [ $? -eq 0 ]; then
    echo "✅ DNS resolution successful"
else
    echo "❌ DNS resolution failed"
fi

# 2. SSH Connection Test
echo -e "\n=== SSH Connection Test ==="
echo "Testing SSH connection to $SERVER_HOST..."
ssh -q $SERVER_USER@$SERVER_HOST exit
if [ $? -eq 0 ]; then
    echo "✅ SSH connection successful"
else
    echo "❌ SSH connection failed"
fi

# 3. Deployment Path Test
echo -e "\n=== Deployment Path Test ==="
echo "Testing deployment path access..."
ssh $SERVER_USER@$SERVER_HOST "
    if [ -d $DEPLOY_PATH ]; then
        echo '✅ Deployment path exists'
        ls -la $DEPLOY_PATH
    else
        echo '❌ Deployment path does not exist'
    fi
"

# 4. PHP Version Test
echo -e "\n=== PHP Version Test ==="
echo "Checking PHP version on server..."
ssh $SERVER_USER@$SERVER_HOST "php -v"

# 5. Web Server Test
echo -e "\n=== Web Server Test ==="
echo "Testing web server response..."
curl -I "https://$SERVER_HOST" 2>/dev/null | head -n 1

echo -e "\n=== Test Complete ==="
echo "If any tests failed, please verify your Zone.ee configuration"
