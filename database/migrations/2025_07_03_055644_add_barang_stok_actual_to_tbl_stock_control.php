<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_stock_control', function (Blueprint $table) {
            $table->decimal('barang_stok_actual', 10, 2)->default(0)->after('stock_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_stock_control', function (Blueprint $table) {
            $table->dropColumn('barang_stok_actual');
        });
    }
};
