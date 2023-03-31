# laravel-jwt-api

# API Doc
- https://app.swaggerhub.com/templates-docs/TIGERTOPDEV714_1/laravel-jwt-api/1.0.0

1. Please check PHP verison 
    - You must use ^7.3|^8.0

2. composer install

3. Publish JWTtoken
    - php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
    - php artisan jwt:secret

4. php artisan serve