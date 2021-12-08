<?php

use App\Http\Controllers\apicontroller;
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

Route::get('/users',[apicontroller::class,'user']);
Route::post('/registar',[apicontroller::class,'registar']);
Route::post('/login',[apicontroller::class,'login']);

Route::group(['middleware'=>['auth:sanctum']],function (){
    Route::get('/user',[apicontroller::class,'user']);
    Route::get('/logout',[apicontroller::class,'logout']);
});
