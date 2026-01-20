<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome'); 
})->name('home');

Route::get('/invoice', [InvoiceController::class, 'create'])->name('invoice.form');
Route::post('/invoice', [InvoiceController::class, 'store'])->name('invoice.submit');
