## Start
PHP Version : 8.*
Laravel Version : 9.x

composer install

Setup environment variables accordingly from .env.example and .env file attached in example
.The production .env file will be attached to with the email

php artisan serve


php artisan queue:work

Navigate to ``http://localhost/``


### running tests
php artisan test

### running browser tests

php artisan dusk

