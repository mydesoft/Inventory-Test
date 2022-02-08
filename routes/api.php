<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:api']], function(){
	Route::get('/read-inventory', [UserController::class, 'readInventory']);
	Route::post('/add-to-cart', [UserController::class, 'addToCart']);
	Route::post('/logout', [AuthenticationController::class, 'logout']);
});

Route::get('/v1/inventory', [InventoryController::class, 'getAllInventory']);
Route::post('/v1/inventory', [InventoryController::class, 'createInventory']);
Route::get('/v1/inventory/{id}', [InventoryController::class, 'getSingleInventory']);
Route::patch('/v1/inventory/{id}', [InventoryController::class, 'updateInventory']);
Route::delete('/v1/inventory/{id}', [InventoryController::class, 'deleteInventory']);
