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
        Schema::create('stock_opname_requests', function (Blueprint $table) {
            $table->uuid('stock_id')->primary();
            $table->string('request_code', 20)->unique(); // Format: SO-xxxxxxxx
            $table->date('request_date'); // tanggal request
            $table->enum('status_request', ['pending', 'approve', 'reject'])->default('pending');
            $table->string('keterangan', 500)->nullable();
            $table->integer('user_id'); // user yang membuat request (picker)
            $table->integer('approved_by')->nullable(); // user yang menyetujui request
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')
                ->references('user_id')
                ->on('tbl_user')
                ->onDelete('cascade');

            $table->foreign('approved_by')
                ->references('user_id')
                ->on('tbl_user')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_opname_requests');
    }
};
