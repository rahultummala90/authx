<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// Route::middleware('auth:api')->get('/dashboard', function (Request $request) {
//     // TODO - Move this to controller when implementing dashboard features
//     return response()->json([
//         'message' => 'Welcome to your dashboard',
//         'user' => $request->user(),
//     ]);
// });

Route::middleware(['auth:api', 'passport.scope:admin'])->get('/admin/dashboard', function () {
    return response()->json(['message' => 'Welcome, admin']);
});

Route::middleware(['auth:api', 'passport.scope:user'])->get('/dashboard', function () {
    return response()->json(['message' => 'Welcome, user']);
});




Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
    ]);
});
