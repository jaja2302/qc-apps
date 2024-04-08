<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiqcController;
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

Route::get('/history', [ApiqcController::class, 'getHistoryedit']);
Route::post('/plotmaps', [ApiqcController::class, 'plotmaps']);
Route::get('/testapi', [ApiqcController::class, 'testapi']);
Route::get('/getdatacron', [ApiqcController::class, 'getdatacron']);
Route::post('/recordcronjob', [ApiqcController::class, 'recordcronjob']);
Route::get('/checkcronjob', [ApiqcController::class, 'checkcronjob']);
