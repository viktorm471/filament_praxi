
## installation
composer create-project laravel/laravel myapp
composer install
php artisan key:generate



 ### FILAMENT INSTALLATION

composer require filament/filament:"~4.0"

php artisan filament:install --panels

- do the migrations
  php artisan migrate

- create user
  php artisan make:filament-user

- publish config
  php artisan vendor:publish --tag=filament-config

- install vite
    npm install 

- change the composer json to run only vite and php
npm install concurrently --save-dev
"dev": "concurrently \"php artisan serve\" \"vite\""

+ increase filament speed in php.ini

zend_extension=opcache
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000

+ change filament lanaguage
php artisan vendor:publish --tag=filament-translations

+ set .env app_locale and config/app.php
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_CO


## DEVELOPMENT

+ make a filament resource 
*** you can use a model for create a resource and in the prompt of this command specify the name of the model ***
php artisan make:filament-resource UserResource


+ make a single page for customization
php artisan make:filament-page gestionar

+ make action importer
https://filamentphp.com/docs/4.x/actions/import
php artisan make:filament-importer Appoinment

+ make the table to all imports 
php artisan make:queue-batches-table
php artisan make:notifications-table
php artisan vendor:publish --tag=filament-actions-migrations
php artisan migrate

+ starts the queue worker
php artisan queue:work


    