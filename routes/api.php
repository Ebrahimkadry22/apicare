<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostStatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserReviewController;
use App\Models\Pharmacy;
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


Route::prefix('auth/')->group(function () {

        Route::controller(AdminController::class)->prefix('admin')->group(function () {
            Route::post('/register','register');
            Route::post('/login','login');
            Route::post('/logout','logout');


        });
        Route::controller(UserController::class)->prefix('user')->group(function () {
            Route::post('/register','register');
            Route::post('/login','login');
            Route::post('/logout','logout');
        });
        Route::controller(ClientController::class)->prefix('client')->group(function () {
            Route::post('/register','register');
            Route::post('/login','login');
            Route::post('/logout','logout');
        });
});
Route::controller(PostController::class)->prefix('post/')->group(function () {
    Route::post('add','store')->middleware(['auth:user']);
    Route::get('show/{id}','show');
    Route::post('delete/{id}','delete')->middleware(['auth:user']);
    Route::get('approved','approved');
});

Route::prefix('admin/post')->group(function () {
    Route::controller(PostStatusController::class)->group(function () {
        Route::post('/status','postsStatus')->middleware(['auth:admin']);
        Route::post('/status/allposts','postStatusAll')->middleware(['auth:admin']);
    });
});

Route::prefix('client/')->group(function () {
 Route::controller(ClientOrderController::class)->prefix('order/')->group(function () {
    Route::post('add','addOrder')->middleware(['auth:client']);
    Route::get('showOrder','showOrderClient')->middleware(['auth:client']);
    Route::post('delete/{id}','deleteOrder')->middleware(['auth:client']);
    Route::post('deleteAll','deleteAllOrder')->middleware(['auth:client']);
 });
});


Route::controller(UserOrderController::class)->prefix('user/')->group(function () {
    Route::get('pending/orders','userOrder')->middleware(['auth:user']);
    Route::post('order/status','statusOrder')->middleware(['auth:user']);
});

Route::prefix('client/')->group(function () {
    Route::controller(UserReviewController::class)->prefix('review/')->group(function () {
        Route::post('/add','store')->middleware(['auth:client']);
    });
});

Route::controller(UserReviewController::class)->prefix('review/')->group(function () {
        Route::get('post/{id}','postRate');
});
Route::controller(UserProfileController::class)->prefix('user/')->group(function () {
        Route::get('profile','userProfile');
        Route::post('updateprofile','update')->middleware(['auth:user']);
        Route::post('delete/posts','delete')->middleware(['auth:user']);
});

Route::controller(AdminNotificationController::class)
    ->middleware(['auth:admin'])
    ->prefix('admin/notifications')
    ->group(
        function () {
            Route::get('/all', 'index');
            Route::get('/unread', 'unread');
            Route::post('/markReadAll', 'markReadAll');
            Route::delete('/deleteAll', 'deleteAll');
            Route::delete('/delete/{id}', 'delete');
        }
    );

Route::controller(LabController::class)->prefix('lab/')->group(function () {
        Route::post('registered','store');
        Route::get('all','allLabs');
});

Route::controller(PharmacyController::class)->prefix('pharmacy/')->group(function () {
        Route::post('registered','store');
        Route::get('all','allPharmacy');
});

