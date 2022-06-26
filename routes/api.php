<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;

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
Route::get('products',  [ProductController::class, 'list'] );
Route::get('products/{id}',  [ProductController::class, 'getProduct'] );


//Auth token based access
Route::middleware('auth:api')->group(function(){
//API POST: /products route - class ProductController store method to create a product
  Route::post('products',  [ProductController::class, 'store'] );
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
