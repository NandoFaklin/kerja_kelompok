<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\api\ApiKelompokController;
use App\Http\Controllers\api\ApiAnggotaController;
use App\Http\Controllers\api\ApiTaskController;


//=================AUTH========================//
//register
Route::post('Register',[ApiController::class,"Register"]);
//login
Route::post('Login',[ApiController::class,"Login"]);
//profile and logout bearer
Route::group([
    "middleware" => ["auth:sanctum"]
],function(){
    Route::get("Profile",[ApiController::class,"Profile"]);

    //Logout
    Route::get("Logout",[ApiController::class,"Logout"]);
});
//email check
Route::post('checkEmail',[ApiController::class,"checkEmail"]);
//show by nim
//show by nim
Route::get('showuserbynim', [ApiController::class, "showUserByNimOrNip"]);
//=================AUTH========================//


//=================Kelompok===================//
//show kelompok
Route::get('showkelompok',[ApiKelompokController::class,"showkelompok"]);
//show kelompok by id kelompok
Route::get('showById',[ApiKelompokController::class,"showById"]);
//show kelompok by nim_or nip
Route::get('showkelompokbynimornip',[ApiKelompokController::class,"showByNimOrNip"]);
//create kelompok
Route::post('createkelompok',[ApiKelompokController::class,"create"]);
//Delete kelompok
Route::post('deletekelompok',[ApiKelompokController::class,"delete"]);
//Update deskripsi kelompok
Route::post('updatekelompok',[ApiKelompokController::class,"update"]);
//=================Kelompok===================//


//===============anggota======================//
//show anggota by id kelompok
Route::get('showAnggotaByKelompokbyid',[ApiKelompokController::class,"showAnggotaByKelompokbyid"]);
//add Anggota
Route::post('addanggota',[ApiAnggotaController::class,"add"]);
//show by id
Route::get('showbyid',[ApiAnggotaController::class,"showById"]);
//delete anggota
Route::post('deleteanggota',[ApiAnggotaController::class,"delete"]);
//===============anggota======================//


//==========task==========================//
//add task
Route::post('addtask',[ApiTaskController::class,"add"]);
//show by id
Route::get('showtaskbyid',[ApiTaskController::class,"showByIdKelompok"]);
//show by nim user
Route::get('showtaskbynim',[ApiTaskController::class,"showByNipOrNipTask"]);
//delete task
Route::post('deletetask',[ApiTaskController::class,"delete"]);
//update task
Route::post('updatetask',[ApiTaskController::class,"update"]);
//==========task==========================//