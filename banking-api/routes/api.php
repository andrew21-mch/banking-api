<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserTransactionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('logout', [UserAuthController::class, 'logout']);
});


Route::group(['prefix' => 'branch', 'middleware'=>['auth:sanctum', 'role:super|admin']], function () {
    Route::resource('/', BranchController::class);
    Route::put('role', [UserAuthController::class, 'updateRole']);
});


Route::group(['prefix' => 'transactions', 'middleware'=>['auth:sanctum', 'role:customer']], function () {
    Route::post('deposit', [UserTransactionController::class, 'deposit']);
    Route::post('withdraw', [UserTransactionController::class, 'withdraw']);
    Route::post('transfer', [UserTransactionController::class, 'transfer']);
    Route::post('balance', [UserTransactionController::class, 'balance']);
    Route::get('statement/{id}', [UserTransactionController::class, 'statement']);
});

Route::group(['middleware'=>['auth:sanctum', 'role:superadmin|admin|employee|customer']], function () {
    Route::get('branch', [BranchController::class, 'index']);
    Route::get('branch/{id}', [BranchController::class, 'show']);
});

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);