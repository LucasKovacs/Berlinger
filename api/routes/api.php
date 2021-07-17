<?php

use App\Http\Controllers\PictureController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Requirement 2: Retrieving the metadata for ALL the already uploaded pictures (JSON response preferred)
Route::get('pictures', PictureController::class . '@index');

// Requirement 3: Retrieving one single image based on his unique identifier
Route::get('pictures/{picture}', PictureController::class . '@show');

// Requirement 1: Upload of the data, by POSTing a CSV file
Route::post('pictures/upload', PictureController::class . '@store');
