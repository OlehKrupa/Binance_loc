<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Installation and first start

### 1. Clone the project repository:
 git clone https://github.com/OlehKrupa/Binance.loc

### 2. Navigate to the project folder:
 cd your-project

### 3. Install the dependencies using Composer:
 composer install

### 4. Create a .env file based on the .env.example file and configure the database connection.

### 5. Generate an application key:
 php artisan key:generate

### 6. Run the database migration and seeders:
 php artisan migrate:refresh --seed

### 7. Run updating cryptocurrency rates
 php artisan currency:update-history

## Other commands

### Run to send emails with daily cryptocurrency price dynamics
 php artisan email:daily-crypto

### Use to run schedule commands on the background
 php artisan schedule:work &