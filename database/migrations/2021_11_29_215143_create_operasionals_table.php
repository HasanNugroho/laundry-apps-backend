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
            $table->uuid('idpesanan');
            $table->string('jenis');
            $table->string('jenis_service');
            $table->string('kasir', 100);
            $table->text('keterangan');
            $table->integer('nominal');
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
