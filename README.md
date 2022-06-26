<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## UPayments Case Study

You need to Create APIs for an application. We require these APIs to be written using the latest version of PHP Laravel and using the latest features available in the framework.


## Endpoints

Expected functionalities are as follows:

| **Module** | **Description**                                                                   | **API**              |
|------------|-----------------------------------------------------------------------------------|----------------------|
| Auth       | API to login using email/password and get token to subsequent calls if logged in. | POST: /auth/login    |
| Products   | API to create the product by category and store it in DB.                         | POST: /products      |
| Products   | API to get the products                                                           | GET: /products       |
| Products   | API to get a single product detail                                                | POST: /products/{id} |
| Products   | API to delete a product from the list                                             | DEL: /products/{id}  |
| Cart       | API to add cart items (guest and auth)                                            | POST: /cart          |
| Cart       | API to update existing cart items by session (guest and auth)                     | PUT: /cart/{id}      |
| Cart       | API to delete cart items (guest and auth)                                         | DEL: /cart/{id}      |
| Cart       | API to show cart items (guest and auth)                                           | GET: /cart           |

## API Documentation

 Please visit the [Postman documentation](https://documenter.getpostman.com/view/16177425/UzBsHPsg).

# Installation

Install the dependencies and start the server to test the Api.

```sh
$ Composer install
$ php artisan migrate
$ php artisan passport:install
$ php artisan migrate
$ php artisan db:seed
```

