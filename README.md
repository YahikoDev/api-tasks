# Laravel-11 API

Laravel API to management tasks.

## Installation
Installation to development environment

```bash
# clone repo
git clone https://github.com/YahikoDev/api-tasks.git
```
```bash
# Install composer
composer install
```
```bash
# Create .env file
cp .env.example .env
```
```bash
# Generate APP key
php artisan key:generat
```
```bash
# Config .env database variables 
DB_CONNECTION=your_driver_database
DB_HOST=your_host
DB_PORT=your_post
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
```bash
# Create .env JWT_SECRET to use jwt-auth
php artisan jwt:secret
```
```bash
# Execute migrations and seeders
php artisan migrate
php artisan db:seed
```

## Note

```python
#if the documentation does not appear or is incomplete, execute:
php artisan l5-swagger:generate
```