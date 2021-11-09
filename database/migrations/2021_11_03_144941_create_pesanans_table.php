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
            $table->string('nama_pelanggan');
            $table->string('layanan');
            $table->date('deadline');
            $table->string('nota_transaksi');
            $table->string('status');
            $table->string('kategori')->nullable();
            $table->string('whatsapp');
            $table->string('note')->nullable();
            $table->string('paket')->nullable();
            $table->uuid('outletid');
            $table->string('jenis_layanan');
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
