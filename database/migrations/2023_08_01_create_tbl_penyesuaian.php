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
        Schema::create('tbl_penyesuaian', function (Blueprint $table) {
            $table->id('penyesuaian_id');
            $table->string('penyesuaian_kode');
            $table->date('penyesuaian_tanggal');
            $table->tinyInteger('penyesuaian_status')->default(0); // 0: Pending, 1: Approved, 2: Rejected
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('tbl_penyesuaian_detail', function (Blueprint $table) {
            $table->id('penyesuaian_detail_id');
            $table->unsignedBigInteger('penyesuaian_id');
            $table->string('barang_kode');
            $table->integer('stok_tercatat')->nullable();
            $table->integer('stok_fisik')->nullable();
            
            $table->foreign('penyesuaian_id')->references('penyesuaian_id')->on('tbl_penyesuaian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_penyesuaian_detail');
        Schema::dropIfExists('tbl_penyesuaian');
    }
};