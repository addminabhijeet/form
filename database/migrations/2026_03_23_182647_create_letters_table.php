<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();

            $table->string('letter_number')->unique();
            $table->date('letter_date')->nullable();
            $table->date('due_date')->nullable();

            $table->string('candidate_name')->nullable();
            $table->string('candidate_email')->nullable();
            $table->string('candidate_mobile')->nullable();
            $table->text('candidate_address')->nullable();

            $table->string('package')->nullable();
            $table->decimal('install_amt', 10, 2)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('letters');
    }
};
