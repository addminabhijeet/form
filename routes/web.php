<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LetterController;

Route::get('/', [InvoiceController::class, 'home'])->name('home');
Route::get('/email-create', [InvoiceController::class, 'emailcreate'])->name('email.form');
Route::get('/invoice-create', [InvoiceController::class, 'create'])->name('invoice.form');
Route::post('/invoice-store', [InvoiceController::class, 'store'])->name('invoice.submit');
Route::get('/invoice-list', [InvoiceController::class, 'list'])->name('invoice.list');
Route::get('/invoice-pdf/{id}', [InvoiceController::class, 'pdf'])->name('invoice.pdf');
Route::get('/invoice-pdf-one/{id}', [InvoiceController::class, 'pdfone'])->name('invoiceone.pdf');
Route::get('/invoice-pdf-two/{id}', [InvoiceController::class, 'pdftwo'])->name('invoicetwo.pdf');
Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
Route::put('/invoice-update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
Route::delete('/invoice-delete/{id}', [InvoiceController::class, 'destroy'])->name('invoice.delete');
Route::get('/invoice-download/{id}', [InvoiceController::class, 'download'])->name('invoice.download');
Route::get('/invoice/check-email', [InvoiceController::class, 'checkEmail'])->name('invoice.checkEmail');

Route::get('/email-create', [LetterController::class, 'emailcreate'])->name('letter.form');
Route::get('/letter-create', [LetterController::class, 'lettercreate'])->name('letter.form');
Route::post('/letter-store', [LetterController::class, 'letterstore'])->name('letter.submit');
Route::get('/letter-list', [LetterController::class, 'letterlist'])->name('letter.list');
Route::get('/letter-pdf/{id}', [LetterController::class, 'letterpdf'])->name('letter.pdf');
Route::get('/letter-pdf-one/{id}', [LetterController::class, 'letterpdfone'])->name('letterone.pdf');
Route::get('/letter-pdf-two/{id}', [LetterController::class, 'letterpdftwo'])->name('lettertwo.pdf');
Route::get('/letter/{id}/edit', [LetterController::class, 'letteredit'])->name('letter.edit');
Route::put('/letter-update/{id}', [LetterController::class, 'letterupdate'])->name('letter.update');
Route::delete('/letter-delete/{id}', [LetterController::class, 'letterdestroy'])->name('letter.delete');
Route::get('/letter-download/{id}', [LetterController::class, 'letterdownload'])->name('letter.download');
Route::get('/letter/check-email', [LetterController::class, 'lettercheckEmail'])->name('letter.checkEmail');
