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
        //
        Schema::create('tbl_stock_control_details', function (Blueprint $table) {
            $table->uuid('stock_detail_id')->primary();
            $table->uuid('stock_id');
            $table->unsignedInteger('barang_id')->comment('Referensi ke barang_id di tbl_barang');
            $table->decimal('stock_system', 10, 2)->nullable();
            $table->decimal('stock_in', 10, 2)->nullable();
            $table->boolean('is_checked')->default(false);
            $table->timestamps();

            // $table->foreign('stock_id')
            //     ->references('stock_id')
            //     ->on('tbl_stock_control')
            //     ->onDelete('cascade');

            // $table->foreign('barang_id')
            //     ->references('barang_id')
            //     ->on('tbl_barang')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('tbl_stock_control_details');
    }
};
