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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name');
            $table->string('drug_class');
            $table->foreignId('medicine_form_id')->constrained();
            $table->string('strength');
            $table->foreignId('medicine_route_id')->constrained();
            $table->string('unit');
            $table->integer('stock');
            $table->decimal('price');
            $table->string('batch_number');
            $table->date('expiry_date');
            $table->string('manufacturer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
