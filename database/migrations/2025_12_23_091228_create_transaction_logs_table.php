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
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')->nullable(); // من قام بالفعل (زبون / مدير / system)

            $table->string('action');
            // created | approved | rejected | executed | failed | interest_applied

            $table->text('description')->nullable();

            $table->json('old_data')->nullable(); // قبل التغيير
            $table->json('new_data')->nullable(); // بعد التغيير
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
