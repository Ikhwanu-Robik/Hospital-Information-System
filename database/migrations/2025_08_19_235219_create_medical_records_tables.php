<?php

use App\Enums\PaymentStatus;
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
        // one medical record - one prescription -< many medicines
        
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('doctor_profile_id')->constrained();
            $table->text('complaint');
            $table->text('diagnosis');
            $table->timestamps();
        });

        Schema::create('prescription_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->unique()->constrained();
            $table->enum('payment_status',array_column(PaymentStatus::cases(), 'value'));
            $table->timestamps();
        });

        Schema::create('prescription_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_record_id')->constrained();
            $table->foreignId('medicine_id')->constrained();
            $table->integer('dose_amount');
            $table->string('frequency');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_medicine');
        Schema::dropIfExists('prescription_records');
        Schema::dropIfExists('medical_records');
    }
};
