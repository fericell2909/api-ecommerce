<?php

use App\Modules\Auth\Models\SupervisorUser;
use App\Modules\Auth\Models\User;
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
        Schema::create(SupervisorUser::TABLE, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supervisor_id')->comment('UserID')->default(0)->nullable(false);
            $table->unsignedBigInteger('user_id')->comment('UserID')->default(0)->nullable(false);
            $table->unsignedInteger('status_id')->comment('status_id of status of an user')->default(1)->nullable(false);
            $table->foreign('supervisor_id', 'fk_supervisor_id_uu_idx')
                ->references('id')->on(User::TABLE);
            $table->foreign('user_id', 'fk_userid_id_ust_idx')
                ->references('id')->on(User::TABLE);

            $table->unique(['supervisor_id', 'user_id','status_id']);

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
        Schema::dropIfExists(SupervisorUser::TABLE);
    }
};
