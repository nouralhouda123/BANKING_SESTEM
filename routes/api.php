
<?php

use App\Http\Controllers\Auth\CitizenAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/citizen/register', [CitizenAuthController::class, 'register']);
Route::post('/citizen/verify-email', [CitizenAuthController::class, 'verifyEmail']);

Route::middleware('auth:sanctum')->group(function () {


});





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
