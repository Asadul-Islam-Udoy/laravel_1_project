<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[App\Http\Controllers\UserController::class,'register_method']);
Route::get('/verify-email/{token}',[App\Http\Controllers\UserController::class,'verify_method']);
Route::post('/login',[App\Http\Controllers\UserController::class,'login_method']);
Route::get('/logout',[App\Http\Controllers\UserController::class,'logout_method']);
Route::get('/all/videos',[App\Http\Controllers\VideoController::class,'get_all_video'])->name('videos');
Route::post('/t',[App\Http\Controllers\VideoController::class,'test_db'])->name('videos');

Route::group(['middleware'=>['api','admin:role']],function($router){
Route::get('/videos',[App\Http\Controllers\VideoController::class,'get_video_admin'])->name('videos');
Route::post('/videos/create',[App\Http\Controllers\VideoController::class,'create_video']);
Route::post('/videos/update/{id}',[App\Http\Controllers\VideoController::class,'update_video']);
Route::delete('/videos/delete/{id}',[App\Http\Controllers\VideoController::class,'delete_video']);  
Route::get('/get/all/users',[App\Http\Controllers\UserController::class,'get_all_users']);
Route::delete('/delete/user/{id}',[App\Http\Controllers\UserController::class,'delete_user']);
Route::post('/permission/user/{id}',[App\Http\Controllers\UserController::class,'permission_user']);
Route::post('/permission/user/role/{id}',[App\Http\Controllers\UserController::class,'role_permision']);
Route::post('/create/groups',[App\Http\Controllers\GroupVideoController::class,'create_group_name']);
Route::get('/get/groups',[App\Http\Controllers\GroupVideoController::class,'get__groups_name']);
Route::post('/create/videos/groups',[App\Http\Controllers\VideoController::class,'group_video_create']);
});


