<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKiloansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiloans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan');
            $table->integer('harga');
            $table->string('idwaktu');
            $table->string('jenis')->nullable();
            $table->boolean('status');
            $table->string('item')->nullable();
            $table->string('idoutlet')->nullable();
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
        Schema::dropIfExists('kiloans');
    }
}
