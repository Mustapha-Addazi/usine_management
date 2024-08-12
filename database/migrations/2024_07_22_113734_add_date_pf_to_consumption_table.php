<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consumptions', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->float('pf')->default(1)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumptions', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('pf');
        });
    }
};
