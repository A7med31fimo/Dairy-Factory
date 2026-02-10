<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('debtor_name');             // اسم المدين
            $table->text('reason');                    // سبب الدين
            $table->decimal('total_amount', 12, 2);   // المبلغ الإجمالي
            $table->decimal('paid_amount', 12, 2)->default(0); // المبلغ المدفوع
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('debt_date');                 // تاريخ الدين
            $table->text('notes')->nullable();         // ملاحظات
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('debt_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);         // مبلغ الدفعة
            $table->date('payment_date');              // تاريخ الدفع
            $table->text('notes')->nullable();         // ملاحظات
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_payments');
        Schema::dropIfExists('debts');
    }
};
