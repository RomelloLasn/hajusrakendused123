@servers(['web' => 'romello@tak22lasn.itmajakas.ee'])

@setup
    $repository = 'git@github.com:RomelloLasn/hajusrakendused123.git';
    $releases_dir = '/home/romello/releases';
    $app_dir = '/home/romello/rakendused1';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
    $shared_dir = '/home/romello/shared';
@endsetup

@story('deploy')
    clone_repository
    setup_environment
    update_symlinks
    optimize
    update_permissions
    migrate
    cleanup
    finish_deploy
@endstory

@task('clone_repository')
    echo 'Cloning repository...'
    [ -d {{ $releases_dir }} ] || mkdir -p {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit ?? 'origin/main' }}
@endtask

@task('setup_environment')
    echo "Setting up environment..."
    cd {{ $new_release_dir }}
    
    # Install dependencies
    composer install --no-dev --optimize-autoloader
    
    # Create proper .htaccess for Zone.ee
    echo "Options +FollowSymLinks -Indexes" > .htaccess
    echo "AddType application/x-httpd-php83 .php" >> .htaccess
    echo "RewriteEngine On" >> .htaccess
    echo "RewriteCond %{REQUEST_URI} !^public" >> .htaccess
    echo "RewriteRule ^(.*)$ public/$1 [L]" >> .htaccess
@endtask

@task('update_symlinks')
    echo 'Linking storage directory...'
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $shared_dir }}/storage {{ $new_release_dir }}/storage
    
    echo 'Linking .env file...'
    ln -nfs {{ $shared_dir }}/.env {{ $new_release_dir }}/.env
    
    echo 'Linking database file...'
    mkdir -p {{ $new_release_dir }}/database
    ln -nfs {{ $shared_dir }}/database.sqlite {{ $new_release_dir }}/database/database.sqlite
@endtask

@task('optimize')
    echo "Optimizing installation..."
    cd {{ $new_release_dir }}
    php artisan optimize:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
@endtask

@task('update_permissions')
    echo "Updating permissions..."
    cd {{ $new_release_dir }}
    chmod -R 775 storage
    mkdir -p storage/framework/sessions
    mkdir -p storage/framework/views
    mkdir -p storage/framework/cache
    chmod -R 775 storage/framework/sessions
    chmod -R 775 storage/framework/views
    chmod -R 775 storage/framework/cache
    chmod -R 775 bootstrap/cache
@endtask

@task('migrate')
    echo "Running migrations..."
    cd {{ $new_release_dir }}
    php artisan migrate --force
@endtask

@task('cleanup')
    echo "Cleaning up old releases..."
    cd {{ $releases_dir }}
    ls -dt */ | tail -n +4 | xargs -d "\n" rm -rf
@endtask

@task('finish_deploy')
    echo 'Linking current release...'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}
    echo "Deployment finished!"
@endtask
