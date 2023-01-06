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

// главная:admin.home | frontend:get | method:admin |view:pages/admin_home.blade
Route::get('/admin/home', [App\Http\Controllers\Telegram\SquirrelKidsBotController::class, 'admin'])->name('admin.home');

// список программ:admin.list.programs | frontenf:get | method:index | view:pages/list_programs.blade
Route::get('/admin/programs', [App\Http\Controllers\ProgramController::class, 'index'])->name('admin.list.programs');
// добавление программы:admin.add.program | frontend:get | method:none | view:pages/form_program.blade
Route::get('/admin/add/program', function () {return view('admin.pages.form_program');})->name('admin.add.program');
// управление программой:admin.control.program | frontend:get | method:show | view:pages/form_program.blade
Route::get('/admin/control/program/{id}', [App\Http\Controllers\ProgramController::class, 'show'])->name('admin.control.program');

// создание программы:admin.create.program | backend:post | method:create | view:none
Route::post('/admin/create/program', [App\Http\Controllers\ProgramController::class, 'create'])->name('admin.create.program');
// изменение программы:admin.edit.program | backend:post | method:edit | view:none
Route::post('/admin/edit/program/{id}', [App\Http\Controllers\ProgramController::class, 'edit'])->name('admin.edit.program');
// изменение программы:admin.delete.program | backend:post | method:destroy | view:none
Route::post('/admin/delete/program/{id}', [App\Http\Controllers\ProgramController::class, 'destroy'])->name('admin.delete.program');

// сообщение с расписанием:telegram.timetable | response view:telegram/timetable.blade
//* view()
Route::get('/admin/look/timetable', [App\Http\Controllers\ProgramController::class, 'look'])->name('admin.look.timetable');
// сообщение с описанием программы:telegram.program | response view:telegram/program.blade
//* view()
Route::get('/admin/look/program/{id}', [App\Http\Controllers\ProgramController::class, 'show'])->name('admin.look.program');