<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus tabel lama jika ada
        Schema::dropIfExists('daily_reports');
        
        // Buat tabel baru dengan struktur lengkap
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('product_name');
            $table->string('category');
            $table->string('product_id');
            $table->integer('quantity_sold')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->decimal('cost', 12, 2)->default(0);
            $table->decimal('margin', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('report_date');
            $table->index('product_name');
            $table->index('category');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }
};