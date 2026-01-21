<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', [InvoiceController::class, 'home'])->name('home');
Route::get('/invoice-create', [InvoiceController::class, 'create'])->name('invoice.form');
Route::post('/invoice-store', [InvoiceController::class, 'store'])->name('invoice.submit');
Route::get('/invoice-list', [InvoiceController::class, 'list'])->name('invoice.list');
Route::get('/invoice-pdf/{id}', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
Route::put('/invoice-update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
Route::delete('/invoice-delete/{id}', [InvoiceController::class, 'destroy'])->name('invoice.delete');
Route::get('/invoice-download/{id}', [InvoiceController::class, 'download'])->name('invoice.download');
