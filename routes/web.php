<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/invoice-create', [InvoiceController::class, 'create'])->name('invoice.form');
Route::post('/invoice-store', [InvoiceController::class, 'store'])->name('invoice.submit');
Route::post('/invoice-pdf', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
