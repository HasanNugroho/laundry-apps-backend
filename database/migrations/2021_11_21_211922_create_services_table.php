<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nama_layanan');
            $table->integer('harga');
            $table->uuid('idwaktu')->nullable();
            $table->string('kategori');
            $table->string('jenis');
            $table->string('item');
            $table->boolean('status');
            $table->uuid('idoutlet')->nullable();
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
        Schema::dropIfExists('services');
    }
}
