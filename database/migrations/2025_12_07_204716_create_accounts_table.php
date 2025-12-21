<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->enum('account_type', ['composite', 'leaf'])->default('leaf')->default('composite');
            $table->decimal('balance', 12, 2)->nullable();
            $table->enum('status', ['active', 'frozen', 'suspended', 'closed', 'blocked'])->default('active');
            $table->enum('type', ['savings', 'checking', 'loan', 'investment']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
