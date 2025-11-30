# 1. Navigate to your project root
cd /path/to/your/project-root

# 2. Fix ownership and permissions
sudo chown -R $USER:$USER ./src
chmod -R u+rwX ./src/storage
chmod -R u+rwX ./src/bootstrap/cache

# 3. Build and start Docker containers
docker-compose up -d --build

# 4. Verify containers are running
docker ps

# 5. Install PHP dependencies with Composer inside container
docker exec -it trulymine_backend composer install

# 6. Create/update .env file inside ./src with your config, example:
# APP_NAME=Laravel
# APP_ENV=local
# APP_KEY=
# APP_DEBUG=true
# APP_URL=http://localhost
# LOG_CHANNEL=stack
# LOG_DEPRECATIONS_CHANNEL=null
# LOG_LEVEL=debug
# DB_CONNECTION=mysql
# DB_HOST=db
# DB_PORT=3306
# DB_DATABASE=your_db_name
# DB_USERNAME=your_db_user
# DB_PASSWORD=your_db_password

# 7. Generate the application key
docker exec -it trulymine_backend php artisan key:generate

# 8. Run database migrations
docker exec -it trulymine_backend php artisan migrate


## Notes:
## Replace /path/to/your/project-root with your actual local path.
## Replace database credentials in .env before running migrations.
## You can clear caches after config changes if needed:

docker exec -it trulymine_backend php artisan config:cache
docker exec -it trulymine_backend php artisan cache:clear
docker exec -it trulymine_backend php artisan view:clear
