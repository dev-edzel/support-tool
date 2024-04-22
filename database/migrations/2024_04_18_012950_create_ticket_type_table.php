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
        Schema::create('ticket_type', function (Blueprint $table) {
            $table->id();
            $table->string('short_name')->unique();
            $table->string('name');
            $table->unsignedBigInteger('last_modified_log_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('ticket_types');
    }
};
