<?php

use App\Modules\Auth\Models\PasswordReset;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PasswordReset::getClassName(), function (Blueprint $table) {
            //$table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('token', 150)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('expiration_date')->nullable();
            $table->string('email', 150)->nullable();
            $table->integer('nstatus')->nullable(false)->default(1);
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists(PasswordReset::getClassName());
    }
}
