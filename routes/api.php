<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CampaignController;


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

Route::group(['middleware' => 'api'], function($routes){
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    Route::post('/profile',[UserController::class,'profile']);
    Route::post('/tokenrefresh',[UserController::class,'refresh']);
    Route::post('/logout',[UserController::class,'logout']);
    Route::get('/showusers',[UserController::class,'index']);
    Route::get('/show/{id}',[UserController::class,'show']);
    Route::post('/update/{user}',[UserController::class,'update']);
    Route::delete('/delete/{user}',[UserController::class,'destroy']);


    Route::post('/createcampaign',[UserController::class,'store']);
    Route::get('/showcampaign',[UserController::class,'showCampaignData']);
    Route::post('/updatecampaign/{user}',[UserController::class,'updateCampaign']);
    Route::post('/updateCampaignStatus/{user}',[UserController::class,'updateCampaignStatus']);
    Route::delete('/deletecampaign/{user}',[UserController::class,'deleteCampaign']);
});


/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


