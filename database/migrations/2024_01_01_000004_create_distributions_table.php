<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');              // اسم المحل
            $table->string('driver_name');             // اسم السائق
            $table->string('vehicle_number');          // رقم السيارة
            $table->decimal('total_value', 12, 2);    // القيمة الإجمالية
            $table->dateTime('delivery_date');         // تاريخ ووقت التوصيل
            $table->text('notes')->nullable();         // ملاحظات
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('distribution_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribution_id')->constrained()->onDelete('cascade');
            $table->string('product_name');            // اسم المنتج
            $table->decimal('quantity', 10, 2);       // الكمية
            $table->string('unit')->default('وحدة');  // الوحدة
            $table->decimal('unit_price', 8, 2);      // سعر الوحدة
            $table->decimal('subtotal', 12, 2);       // المجموع الفرعي
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_items');
        Schema::dropIfExists('distributions');
    }
};
