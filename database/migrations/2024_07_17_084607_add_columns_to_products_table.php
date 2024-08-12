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
        Schema::table('products', function (Blueprint $table) {
            $table->float('covering_day')->default(0)->nullable(false);
            $table->float('stock')->default(0)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodcts', function (Blueprint $table) {
            $table->dropColumn('covering_day');
            $table->dropColumn('stock');
        });
    }
};
