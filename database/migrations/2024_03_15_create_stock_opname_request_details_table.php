<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_opname_request_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('stock_id'); // foreign key ke stock_opname_requests
            $table->integer('barang_id');
            $table->decimal('stock_system', 10, 2)->default(0);
            $table->decimal('stock_in', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable(); // selisih antara system dan actual
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('stock_id')
                ->references('stock_id')
                ->on('stock_opname_requests')
                ->onDelete('cascade');

            $table->foreign('barang_id')
                ->references('barang_id')
                ->on('tbl_barang')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opname_request_details');
    }
};
