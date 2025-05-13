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
        Schema::create('tbl_stock_control', function (Blueprint $table) {
            $table->uuid('stock_id')->primary();
            $table->decimal('stock_in', 10, 2)->nullable();
            $table->enum('status_request', ['pending', 'approve', 'reject'])->default('pending');
            $table->string('keterangan', 500)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Tanpa foreign key untuk sementara
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_stock_control');
    }
};
