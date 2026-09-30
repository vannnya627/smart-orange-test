### Clone project:
```
git clone https://github.com/vannnya627/smart-orange-test.git
```
---
### Start Docker containers:
```
docker compose up -d
```
---
### Install PHP dependencies:
```
 docker compose exec php-fpm composer install
```
---
### Run database migrations:
```
docker compose exec php-fpm php artisan migrate --no-interaction
```
### https://localhost/import or http://localhost/import
