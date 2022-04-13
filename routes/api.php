<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\UploadBookController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;






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


Route::post('viewBooks',[UploadBookController::class,'viewBooks']);

Route::post('swap_books',[UploadBookController::class,'sendNotification']);

Route::post('postBook',[UploadBookController::class,'uploadBooks']);

Route::post('view_notification',[UploadBookController::class,'getNotification']);

Route::post('acceptReq',[UploadBookController::class,'acceptReq']);

Route::post('acceptSingleReq',[UploadBookController::class,'acceptSingleReq']);

Route::post('rejectReq',[UploadBookController::class,'rejectReq']);


Route::get('messages',[UploadBookController::class,'getMessages']);


Route::get('check',[UploadBookController::class,'getMessages']);


//Route::post('sendMessage',[ChatController::class,'sendMessage']);

Route::get('list_users',[ChatController::class,'list_users']);

Route::post('getMessages',[ChatController::class,'getMessages']);

Route::post('getPusherPath',[ChatController::class,'getPusherPath']);
Route::post('sendMessages',[ChatController::class,'sendMessages']);
Route::get('getName/{email}',[ChatController::class,'getName']);


Route::post('profile',[ProfileController::class,'getMyInfo']);

Route::post('check',[ProfileController::class,'check']);


Route::post('myBooks',[ProfileCOntroller::class,'myBooks']);

Route::post('insertLocation',[UploadBookController::class,'insertLocation']);


Route::post('updateLocation',[UploadBookController::class,'uploadLocation']);

Route::post('search',[UploadBookController::class,'search']);

//--------------------profile-----------------------------


Route::get('profile',[ProfileController::class,'myProfile']);