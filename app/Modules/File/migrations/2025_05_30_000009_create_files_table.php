<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->string('nombre_original', 255)->nullable(false)->comment('nombre original del archivo subido.');
            $table->double('tamanio',16,0)->nullable(true)->default(0)->comment('tamaño del archivo');
            $table->string('extension',500)->nullable(false)->comment('extension del archivo subido');
            $table->string('tipo',500)->nullable(false)->comment('mimetype del archivo subido');
            $table->string('hash',25)->unique()->nullable(false)->comment('hash del archivo subido. Generado al momento de crear el registro');
            $table->string('ruta_url',500)->nullable(false)->comment('url del archivo subido en S3.');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status');
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
        Schema::drop('files');
    }
}
