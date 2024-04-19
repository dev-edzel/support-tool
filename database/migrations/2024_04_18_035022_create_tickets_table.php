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
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('address');
            $table->string('number');
            $table->string('email');
            $table->unsignedBigInteger('ticket_type_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('subject');
            $table->string('ref_no');
            $table->string('concern');
            $table->string('status')->default('OPEN');
            $table->string('resolved_by')->nullable();
            $table->dateTime('resolved_date')->nullable();
            $table->dateTime('closed_date')->nullable();
            $table->unsignedBigInteger('last_modified_log_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ticket_type_id')
                ->references('id')
                ->on('ticket_types');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories');
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
