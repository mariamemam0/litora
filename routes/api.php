<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class ,'register']);
Route::post('/login',[AuthController::class ,'login']);

Route::middleware('auth:sanctum')->group(function (){
    //logout endpoint
    Route::post('/logout',[AuthController::class , 'logout']);

    Route::post('email/verification-notification', function(Request $request){
        if($request->user()->hasverifiedEmail()){
            return response()->json(['message' => 'Email already verified'], 200);
        };

        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email sent'], 200);
    });

    Route::get('email/verify/{id}/{hash}',function(EmailVerificationRequest $request){
        $request->fulfill();
        return response()->json(['message' => 'Email verified successfully'], 200);
    })->name('verification.verify')->middleware(['signed']);
});


