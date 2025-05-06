<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->prefix('auth/user')->group(function () {
    Route::put('/update', [UserController::class, 'update']);
});

require __DIR__.'/auth/mail.php';
require __DIR__.'/auth/phone.php';