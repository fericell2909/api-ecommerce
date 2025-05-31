<?php

use App\Modules\Api\Models\Status;
use App\Modules\Auth\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(User::getClassName(), function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('surnames',250);
            $table->string('email')->unique();
            $table->boolean('request_new_password')->default(true)->comment('true: indica que debe cambiar contraseña, false: indica que no debe cambiar contraseña.');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('rut', 50)->nullable(false)->default('')->comment('no han deseado el rut');
            $table->unsignedBigInteger('file_id')->comment('file_id of avatar in files')->default(0)->nullable(true);
            $table->unsignedBigInteger('status_id')->comment('status_id of status of an user')->default(1)->nullable(false);
            $table->foreign('status_id','users_status_id_foreign')->references('id')->on(Status::TABLE);
            $table->rememberToken();
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
        Schema::dropIfExists(User::getClassName());
    }
}
