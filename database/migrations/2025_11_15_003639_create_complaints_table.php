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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // حقل الجهة المسؤولة (باستخدام ENUM)
            $table->enum('department', [
                'Environment',
                'Municipality',
                'Health',
                'Traffic',
                'Other'
            ])->comment('الجهة المسؤولة عن الشكوى');

            $table->string('title'); // عنوان الشكوى
            $table->text('description'); // نص الشكوى

            // مسار الملف المرفق (يمكن أن يكون فارغاً)
            $table->string('attachment_path')->nullable()->comment('مسار حفظ الملف المرفق (صورة/ملف)');

            // حالة الشكوى الافتراضية تكون "Pending"
            $table->enum('status', ['Pending', 'In Progress', 'Resolved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
