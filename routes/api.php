<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiqcController;
use App\Http\Middleware\WhitelistIpMiddleware;
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
Route::middleware([WhitelistIpMiddleware::class])->group(function () {
    Route::get('/history', [ApiqcController::class, 'getHistoryedit']);
    Route::post('/plotmaps', [ApiqcController::class, 'plotmaps']);
    Route::get('/testapi', [ApiqcController::class, 'testapi']);
    Route::get('/getdatacron', [ApiqcController::class, 'getdatacron']);
    Route::post('/recordcronjob', [ApiqcController::class, 'recordcronjob']);
    Route::get('/checkcronjob', [ApiqcController::class, 'checkcronjob']);
    Route::get('/sendwamaintence', [ApiqcController::class, 'sendwamaintence']);
    Route::post('/changestatusmaintence', [ApiqcController::class, 'changestatusmaintence']);
    Route::post('/getnamaatasan', [ApiqcController::class, 'getnamaatasan']);
    Route::get('/getunitdata', [ApiqcController::class, 'get_unit_bagian']);
    Route::post('/getuserinfo', [ApiqcController::class, 'getuserinfo']);
    Route::post('/formdataizin', [ApiqcController::class, 'form_data_ijin']);
    Route::get('/getdatamill', [ApiqcController::class, 'get_data_mill']);
    Route::post('/updatedatamill', [ApiqcController::class, 'get_data_mill_update']);
    Route::get('/getnotifijin', [ApiqcController::class, 'getnotif_suratijin']);
    Route::post('/inputiotdata', [ApiqcController::class, 'inputiot_data']);
    Route::post('/updatenotifijin', [ApiqcController::class, 'getnotif_suratijin_approved']);
    Route::get('/getmsgsmartlabs', [ApiqcController::class, 'getmsgsmartlabs']);
    Route::post('/deletemsgsmartlabs', [ApiqcController::class, 'deletemsgsmartlabs']);
    Route::post('/updatestatusbot', [ApiqcController::class, 'updatestatusbot']);
    Route::get('/checkPcStatus', [ApiqcController::class, 'checkPcStatus']);
    Route::get('/getlistestate', [ApiqcController::class, 'getlistestate']);
});
