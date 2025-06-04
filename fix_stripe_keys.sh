#!/bin/bash

# Stripe Keys Fix Script
# This script checks and fixes Stripe API key issues in .env file

echo "Checking Stripe API keys in .env file..."

# Path to the .env file
ENV_FILE="/home/romello/rakendused1/.env"
ENV_BACKUP="${ENV_FILE}.backup"

# Check if .env file exists
if [ ! -f "$ENV_FILE" ]; then
    echo "ERROR: .env file not found at $ENV_FILE"
    exit 1
fi

# Create a backup of the current .env file
cp "$ENV_FILE" "$ENV_BACKUP"
echo "Created backup of .env file at $ENV_BACKUP"

# Clean up the Stripe keys (remove any trailing spaces or invisible characters)
STRIPE_KEY=$(grep "^STRIPE_KEY=" "$ENV_FILE" | cut -d= -f2- | tr -d " \t\r\n")
STRIPE_SECRET=$(grep "^STRIPE_SECRET=" "$ENV_FILE" | cut -d= -f2- | tr -d " \t\r\n")

# Check if keys were found
if [ -z "$STRIPE_KEY" ]; then
    echo "WARNING: STRIPE_KEY not found in .env file"
else
    echo "Found STRIPE_KEY with length: ${#STRIPE_KEY}"
fi

if [ -z "$STRIPE_SECRET" ]; then
    echo "WARNING: STRIPE_SECRET not found in .env file"
else
    echo "Found STRIPE_SECRET with length: ${#STRIPE_SECRET}"
fi

# Create a temporary file with cleaned keys
TMP_FILE=$(mktemp)
cat "$ENV_FILE" | grep -v "^STRIPE_KEY=" | grep -v "^STRIPE_SECRET=" > "$TMP_FILE"
echo "STRIPE_KEY=$STRIPE_KEY" >> "$TMP_FILE"
echo "STRIPE_SECRET=$STRIPE_SECRET" >> "$TMP_FILE"

# Replace the original file
mv "$TMP_FILE" "$ENV_FILE"

echo "Stripe API keys have been cleaned and fixed."
echo "Clearing Laravel cache..."

# Clear Laravel cache
cd /home/romello/rakendused1
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "Done. Remember to check the website to confirm the fix worked."
