<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->text('candidate_address');
            $table->enum('package', ['career_starter','growth_package','career_acceleration']);
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('bank_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
