<?php

use App\Enums\CheckUpStatus;
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
        Schema::table('check_up_queues', function (Blueprint $table) {
            $table->enum('status', array_column(CheckUpStatus::cases(), 'value'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_up_queues', function (Blueprint $table) {
            //
        });
    }
};
