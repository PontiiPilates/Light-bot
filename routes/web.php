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

// Route::get('/', function () {
//     return view('welcome');
// });

/**
 * Telegram-бот.
 */

// вебхук
Route::post('/tg/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'webhook']);
// установка вебхука
Route::get('/tg/set/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'set']);
// удаление вебхука
Route::get('/tg/delete/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'delete']);
// информация о вебхуке
Route::get('/tg/info/webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'info']);

/**
 * SquirrelKidsBot.
 * При добавлениие нового вебхука не забудь исключить маршрут из csrf-добавлений.
 */

// вебхук
Route::post('/tg/6bf533cfeff238e0d7d265a69a7018ad/webhook', [App\Http\Controllers\Telegram\SquirrelKidsBotController::class, 'webhook']);
// установка вебхука
Route::get('/tg/6bf533cfeff238e0d7d265a69a7018ad/set/webhook', [App\Http\Controllers\Telegram\SquirrelKidsBotController::class, 'set']);
// удаление вебхука
Route::get('/tg/6bf533cfeff238e0d7d265a69a7018ad/delete/webhook', [App\Http\Controllers\Telegram\SquirrelKidsBotController::class, 'delete']);
// информация о вебхуке
Route::get('/tg/6bf533cfeff238e0d7d265a69a7018ad/info/webhook', [App\Http\Controllers\Telegram\SquirrelKidsBotController::class, 'info']);

/**
 * Маршруты админки.
 */

Route::get('/admin/home', [App\Http\Controllers\Telegram\SquirrelKidsBotController::class, 'admin'])->name('admin.home');

/**
 * Маршруты управления программами.
 */

use App\Http\Controllers\ProgramController;

// список программ
Route::get('/admin/index/programs', [ProgramController::class, 'index'])->name('admin.programs.index');

// добавление программы
Route::match(['get', 'post'], '/admin/create/program', [ProgramController::class, 'create'])->name('admin.program.create');
// редактирование программы
Route::match(['get', 'post'], '/admin/edit/program/{id}', [ProgramController::class, 'edit'])->name('admin.program.edit');
// удаление программы
Route::post('/admin/destroy/program/{id}', [ProgramController::class, 'destroy'])->name('admin.program.destroy');

// просмотр программы
Route::get('/admin/show/program/{id}', [ProgramController::class, 'show'])->name('admin.program.show');
// просмотр расписания
Route::get('/admin/show/timetable', [ProgramController::class, 'show'])->name('admin.timetable.show');
