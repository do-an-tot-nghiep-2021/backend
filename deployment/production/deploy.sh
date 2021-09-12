echo "Deploying application >> Production"

# Turn on maintenance mode
echo "Turn on maintenance mode"
php artisan down || true

# Pull the latest changes from the git repository
# git reset --hard
# git clean -df
echo "Pull the latest changes from the git repository"
git pull origin master

# Copy env file
echo "Copy env file"
cp ./env/.env.production ./.env

# Install/update composer dependecies
echo "Install/update composer dependecies"
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Run database migrations
echo "Run database migrations"
php artisan migrate --force

# Run create symbolic
echo "Run create symbolic"
php artisan storage:link

# Clear caches
echo "Clear caches"
php artisan cache:clear

# Clear expired password reset tokens
echo "Clear expired password reset tokens"
php artisan auth:clear-resets

# Clear and cache routes
echo "Clear and cache routes"
php artisan route:cache

# Clear and cache config
echo "Clear and cache config"
php artisan config:cache

# Clear and cache views
echo "Clear and cache views"
php artisan view:cache

# Install node modules
# npm ci

# Build assets using Laravel Mix
# npm run production

# Run optimize
echo "Run optimize"
php artisan optimize

# Turn off maintenance mode
php artisan up
