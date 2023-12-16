<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BlogApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);

    Route::resource('blog', BlogApiController::class, ['except' => ['update']]);

    Route::post('/blog/{id}', [BlogApiController::class, 'update']);
});

