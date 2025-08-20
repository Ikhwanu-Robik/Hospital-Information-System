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
        Schema::create('medicine_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('medicine_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('drug_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        }); 

        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name');
            $table->foreignId('drug_class_id')->constrained();
            $table->foreignId('medicine_form_id')->constrained();
            $table->string('strength');
            $table->foreignId('medicine_route_id')->constrained();
            $table->string('unit');
            $table->integer('stock')->default(0);
            $table->decimal('price');
            $table->text('batch_number');
            $table->date('expiry_date');
            $table->text('manufacturer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('drug_classes');
        Schema::dropIfExists('medicine_forms');
        Schema::dropIfExists('medicine_routes');
    }
};
