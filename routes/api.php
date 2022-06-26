<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API POST: auth/login route - calss AuthController login method to validate login credentials
Route::post('auth/login',  [AuthController::class, 'login']);


//Get products no auth required
//list all products route
Route::get('products',  [ProductController::class, 'list'] );
//get product details by id
Route::get('products/{id}',  [ProductController::class, 'getProduct'] );

//API POST: /cart route - class CartController  addToCart method to add product to cart
Route::post('cart',  [CartController::class, 'addToCart'] );
//update cart item by product id
Route::put('cart/{id}',  [CartController::class, 'updateCart'] );
//delete cart item by product id
Route::delete('cart/{id}',  [CartController::class, 'deleteCart'] );
//list all cart items
Route::get('cart',  [CartController::class, 'viewCart'] );


//Auth token based access
Route::middleware('auth:api')->group(function(){
//API POST: /products route - class ProductController store method to create a product
  Route::post('products',  [ProductController::class, 'store'] );
//delete product by id with user is authorized with token
  Route::delete('products/{id}',  [ProductController::class, 'delProduct'] );
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
