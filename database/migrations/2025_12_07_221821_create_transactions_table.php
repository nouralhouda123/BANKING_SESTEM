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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->unsignedBigInteger('to_account_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('frequency', ['daily','weekly','monthly'])->nullable()->after('scheduled_at');
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->string('rejection_reason')->nullable();
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'failed',
                'scheduled',
                'completed',
            ])->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
