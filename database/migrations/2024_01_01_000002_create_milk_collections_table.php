<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milk_collections', function (Blueprint $table) {
            $table->id();
            $table->string('farmer_name');          // اسم المزارع
            $table->string('driver_name');           // اسم السائق
            $table->string('vehicle_number');        // رقم السيارة
            $table->decimal('quantity_liters', 10, 2); // الكمية بالليتر
            $table->decimal('price_per_liter', 8, 2);  // سعر الليتر
            $table->decimal('total_amount', 12, 2);    // المبلغ الإجمالي
            $table->dateTime('collection_date');        // تاريخ ووقت الجمع
            $table->text('notes')->nullable();          // ملاحظات
            $table->foreignId('user_id')->constrained(); // المستخدم
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milk_collections');
    }
};
