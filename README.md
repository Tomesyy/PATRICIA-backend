# API DOCUMENTATION

## SETUP

- `git clone https://github.com/Tomesyy/PATRICIA-backend.git`
- `cd PATRICIA-backend`
- `composer install`
- create `.env` file in root directory, paste everything in `.env.example` file and fill all details with your database credentials
- `php artisan jwt:secret`
- `php artisan migrate`
- `php -S localhost:8000 -t public`

## TEST
- run `vendor/bin/phpunit`

[Click here to view API documentation](https://documenter.getpostman.com/view/8050532/TVKBZdw8)

## AUTHOR
[Adetomiwa Adesanya](https://linkedin.com/in/adetomiwaadesanya) (BlackDev)