# Laravel Application for Zone.ee Hosting

This Laravel application is configured for deployment to Zone.ee hosting using Deployer.

## Deployment

The application uses the `deploy.yaml` file for deployment settings. To deploy:

```bash
dep deploy stage
```

## Known Issues and Solutions

### Route Name Conflicts

When using both web and API routes with the same names, Laravel may throw errors. The solution is to use namespaced route names:

```php
// Use web.news.index instead of news.index
Route::get('/news', [NewsController::class, 'index'])->name('web.news.index');
```

### Zone.ee .htaccess Configuration

Zone.ee requires specific .htaccess settings. Use the .htaccess.zone file in this project and ensure the PHP handler is set correctly:

```
# Use AddType instead of AddHandler for PHP 8.3
AddType application/x-httpd-php83 .php
```

### Database Issues

For SQLite databases, ensure:
1. The database file exists and is writable
2. The path is correctly set in .env file
3. The directory exists on the server

### Stripe Payment Issues

If you encounter "Invalid API Key provided" errors with Stripe payments:

1. Check that .env file has the correct Stripe keys with no trailing spaces:
   ```
   STRIPE_KEY=pk_test_your_key_here
   STRIPE_SECRET=sk_test_your_key_here
   ```

2. Run the fix_stripe_keys.sh script to clean up any hidden characters:
   ```bash
   ./fix_stripe_keys.sh
   ```

3. Visit `/stripe/debug` route to verify Stripe configuration

4. Ensure the config cache is cleared:
   ```bash
   php artisan config:clear
   ```

## Troubleshooting

If you encounter 500 errors after deployment:
1. Check Laravel logs: `/home/romello/rakendused1/storage/logs/laravel.log`
2. Ensure correct .htaccess files are in place
3. Make sure storage and cache directories are writable
4. Clear all Laravel caches with:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```