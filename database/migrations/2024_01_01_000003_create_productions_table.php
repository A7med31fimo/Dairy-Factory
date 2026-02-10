<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('product_type');          // نوع المنتج (حليب، زبادي، أخرى)
            $table->string('product_name');          // اسم المنتج
            $table->decimal('quantity', 10, 2);      // الكمية المنتجة
            $table->string('unit')->default('لتر');  // الوحدة
            $table->date('production_date');          // تاريخ الإنتاج
            $table->text('notes')->nullable();        // ملاحظات
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
