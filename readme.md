# Youtube tracker

### Installation

Prerequisites

Have `docker` with `docker-compose` running in your machine.

1. Navigate to the application
```sh
cd youtube-tracker
``` 
2. Create `.env` file, you can create it by copying `.env.example`
```sh
cp .env.example .env
```

3. Build your application with Docker
```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
4. You can now launch the application by launching the docker containers with
```sh
./vendor/bin/sail up
```


5. Bash into the laravel container
```sh
docker exec -it youtube-tracker-master-laravel.test-1 bash
```
5.1 Generate app key
  ```sh
php artisan key:generate
``` 
5.2 Run migrations
  ```sh
php artisan migrate
``` 
5.3 Seed the database
```sh
php artisan db:seed
```
5.4 Start vite server
```sh
npm run dev
```
