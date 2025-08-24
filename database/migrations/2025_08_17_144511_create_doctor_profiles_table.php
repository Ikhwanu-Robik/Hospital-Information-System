<?php

use App\Enums\DoctorStatus;
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
            $table->text('full_name');
            $table->string('license_number')->unique();
            $table->string('phone');
            $table->string('department');
            $table->foreignId('specialization_id')
                ->constrained();
            $table->string('room_number');
            $table->enum('status', array_column(DoctorStatus::cases(), 'value'));
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
