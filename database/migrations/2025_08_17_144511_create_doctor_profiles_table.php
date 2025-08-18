<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');
            $table->string('full_name');
            $table->string('license_number')->unique();
            $table->string('phone');
            $table->string('department');
            $table->foreignId('specialization_id')
                ->constrained();
            $table->string('room_number');
            $table->string('status'); // correspond to App\Enums\DoctorStatus
            // this json will be an array like this
            // [
            //      "Mon 07:00:00 18:00:00",
            //      "Tue 08:00:00 16:00:00",
            // ]
            $table->json('schedule')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
