<?php

use App\Http\Controllers\Checkpoint;
use App\Http\Controllers\Home;
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

Route::get('/', Home::class);
Route::get('/checkpoint/{tokenAuthentication}', [Checkpoint::class, 'index']);
Route::post('/checkpoint/create', [Checkpoint::class, 'create']);
Route::post('/checkpoint/show', [Checkpoint::class, 'show']);
Route::post('/checkpoint/upload-file', [Checkpoint::class, 'fileUpload']);
Route::post('/checkpoint/store', [Checkpoint::class, 'store']);
