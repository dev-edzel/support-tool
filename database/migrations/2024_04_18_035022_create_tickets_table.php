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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_info_id');
            $table->string('ticket_number')->unique();
            $table->string('status')->default('1');
            // '1-OPEN', '2-ASSIGNED', '3-ON HOLD', '4-CANCELLED', '0-CLOSED'
            $table->string('resolved_by')->nullable();
            $table->dateTime('resolved_date')->nullable();
            $table->unsignedBigInteger('last_modified_log_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_info_id')
                ->references('id')
                ->on('ticket_info');

            $table->foreign('last_modified_log_id')
                ->references('id')
                ->on('user_logs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
