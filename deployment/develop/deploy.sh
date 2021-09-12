echo "Deploying application >> Develop"

# Turn on maintenance mode
echo "Turn on maintenance mode"
php artisan down || true

# Pull the latest changes from the git repository
# git reset --hard
# git clean -df
echo "Pull the latest changes from the git repository"
git pull origin develop

# Copy env file
echo "Copy env file"
cp ./env/.env.develop ./.env

# Install/update composer dependecies
echo "Install/update composer dependecies"
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Clear caches
echo "Clear caches"
php artisan optimize:clear
php artisan cache:clear

# Install node modules
# npm ci

# Build assets using Laravel Mix
# npm run production

# Run create symbolic
echo "Run create symbolic"
php artisan storage:link

# Run database migrations
echo "Run database migrations"
php artisan migrate --force

# Clear expired password reset tokens
# echo "Clear expired password reset tokens"
# php artisan auth:clear-resets

# Run optimize
echo "Run optimize"
php artisan optimize

# Cache routes
echo "Cache routes"
php artisan route:cache

# Cache config
echo "Cache config"
php artisan config:cache

# Cache views
echo "Cache views"
php artisan view:cache

# Turn off maintenance mode
php artisan up
