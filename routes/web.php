<?php

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

Route::view('/admin', 'Admin.Pages.home');

/**
 * Telegram-бот
 */

// вебхук
Route::post('/tg/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'webhook']);
// установка вебхука
Route::get('/tg/set/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'set']);
// удаление вебхука
Route::get('/tg/delete/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'delete']);
// информация о вебхуке
Route::get('/tg/info/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'info']);
