<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            // Tambahkan semua kolom yang diperlukan
            $table->date('report_date')->after('id');
            $table->string('product_name')->after('report_date');
            $table->string('category')->after('product_name');
            $table->string('product_id')->after('category');
            $table->integer('quantity_sold')->default(0)->after('product_id');
            $table->decimal('revenue', 12, 2)->default(0)->after('quantity_sold');
            $table->decimal('cost', 12, 2)->default(0)->after('revenue');
            $table->decimal('margin', 5, 2)->default(0)->after('cost');
            $table->text('notes')->nullable()->after('margin');
            
            // Tambahkan index untuk performa
            $table->index('report_date');
            $table->index('product_name');
            $table->index('category');
        });
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            $table->dropColumn([
                'report_date',
                'product_name', 
                'category',
                'product_id',
                'quantity_sold',
                'revenue',
                'cost',
                'margin',
                'notes'
            ]);
            
            // Hapus index
            $table->dropIndex(['report_date']);
            $table->dropIndex(['product_name']);
            $table->dropIndex(['category']);
        });
    }
};