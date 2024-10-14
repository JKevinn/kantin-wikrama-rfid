<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\NfcController;

Route::resource('students', StudentsController::class);
Route::resource('transactions', TransactionsController::class);
Route::get('/read-nfc', [NfcController::class, 'readCard'])->name('read.nfc');

Route::get('/', function () {
    return view('index');
});
