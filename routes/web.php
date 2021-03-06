<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/friends', [HomeController::class, 'friends']);
Route::post('/create-session', [SessionController::class, 'create']);
Route::post('/chat', [ChatController::class, 'chat']);
Route::get('/chats/{session}', [ChatController::class, 'chats']);
Route::patch('/session/{session}/mark-as-read', [SessionController::class, 'read']);
Route::get('/session/{session}/clear', [ChatController::class, 'clear']);

Route::patch('/session/{session}/block', [BlockController::class, 'block']);
Route::patch('/session/{session}/unblock', [BlockController::class, 'unblock']);
