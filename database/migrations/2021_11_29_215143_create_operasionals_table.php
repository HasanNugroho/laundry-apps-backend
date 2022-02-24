<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperasionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operasionals', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('idpesanan')->nullable();
            $table->string('jenis');
            $table->string('jenis_service')->nullable();
            $table->string('kasir', 100);
            $table->string('item_name', 100)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('nominal');
            $table->integer('harga', 11)->nullable();
            $table->integer('jumlah', 4)->nullable();
            $table->uuid('outletid');
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
        Schema::dropIfExists('operasionals');
    }
}
