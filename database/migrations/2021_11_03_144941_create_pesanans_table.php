<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('idwaktu');
            $table->date('deadline');
            $table->string('nota_transaksi');
            $table->string('status');
            $table->string('idlayanan');
            $table->string('idpelanggan');
            $table->string('note')->nullable();
            $table->uuid('outletid');
            $table->integer('jumlah');
            $table->string('kasir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesanans');
    }
}
