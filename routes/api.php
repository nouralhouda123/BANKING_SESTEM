
<?php

use App\Http\Controllers\Auth\CitizenAuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::get('/test-notification', function() {
    $manager = User::role('director')->first();
    $service = new \App\Services\NotificationAddTranscationWithBigBalance();

    $result = $service->sendNotification(
        $manager,
        'Test Notification',
        'This is a test message',
        'test_type',
        ['test' => 'data']
    );

    return response()->json([
        'success' => $result,
        'notifications_count' => \App\Models\Notification::count()
    ]);
});

Route::post('/citizen/register', [CitizenAuthController::class, 'register']);
Route::post('/citizen/verify-email/{user_id}', [CitizenAuthController::class, 'verifyEmail']);
Route::post('login', [CitizenAuthController::class, 'login'])->
middleware('role.throttle');
Route::middleware('auth:sanctum')->group(function () {
    // حسابات
    Route::post('add_client', [\App\Http\Controllers\usercontroller::class, 'add_client']);
    Route::get('show', [\App\Http\Controllers\AccountController::class, 'show']);
    Route::get('showAllGrandSon', [\App\Http\Controllers\AccountController::class, 'showAllGrandSon']);
    Route::get('showAccountChildren', [\App\Http\Controllers\AccountController::class, 'showAccountChildren']);
    Route::post('updateaccount', [\App\Http\Controllers\AccountController::class, 'updateaccount']);
    Route::post('closeAccount', [\App\Http\Controllers\AccountController::class, 'closeAccount']);
    Route::post('createMainAccount', [\App\Http\Controllers\AccountController::class, 'createMainAccount']);
    Route::post('addChildAccount', [\App\Http\Controllers\AccountController::class, 'addChildAccount']);
        Route::post('updateStatusAccount/{id}', [\App\Http\Controllers\AccountController::class, 'updateStatusAccount']);
    Route::post('add_employee', [\App\Http\Controllers\usercontroller::class, 'add_employee']);
            Route::post('add_director', [\App\Http\Controllers\usercontroller::class, 'add_director']);
            Route::post('showAccountchildrenForEm/{id}', [\App\Http\Controllers\AccountController::class, 'showAccountchildrenForEm']);
    Route::post('showAllGrandSonForEmployee/{id}', [\App\Http\Controllers\AccountController::class, 'showAllGrandSonForEmployee']);
            Route::get('getAllAccount', [\App\Http\Controllers\AccountController::class, 'getAllAccount']);
                Route::get('showclient', [\App\Http\Controllers\usercontroller::class, 'showclient']);
    Route::get('showAlldirector', [\App\Http\Controllers\usercontroller::class, 'showAlldirector']);
    Route::get('showAllemployee', [\App\Http\Controllers\usercontroller::class, 'showAllemployee']);
    // معاملات
    Route::get('showTransaction', [\App\Http\Controllers\TransactionController::class, 'showTransaction']);
    Route::get('showallTranscation', [\App\Http\Controllers\TransactionController::class, 'showallTranscation']);
        Route::post('updateStatusTranscation/{id}', [\App\Http\Controllers\TransactionController::class, 'updateStatusTranscation']);
   Route::post('store', [\App\Http\Controllers\TransactionController::class, 'store']);
//اشعارات
    Route::get('showNotificationManager', [\App\Http\Controllers\NotificationTestController::class, 'showNotificationManager']);
    Route::post('updateDeviceTokenPatient', [\App\Http\Controllers\NotificationTestController::class, 'updateDeviceTokenPatient']);
    Route::post('logout', [CitizenAuthController::class, 'logout']);
});
