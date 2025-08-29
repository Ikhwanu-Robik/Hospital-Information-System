<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->text('full_name');
            $table->string('NIK');
            $table->date('birthdate');
            $table->enum('gender', ["male", "female"]);
            $table->text('address');
            $table->boolean('marriage_status');
            $table->string('phone');
            $table->string('BPJS_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
