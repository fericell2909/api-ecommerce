<?php

use App\Modules\PermissionsRoles\Models\Menu;
use App\Modules\PermissionsRoles\Models\Role;
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
        Schema::create(Menu::getClassName(), function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->text('title');
            $table->integer('order')->nullable(false)->default(0);
            $table->string('icon', 50)->nullable(false)->default('');
            $table->string('color', 50)->nullable(false)->default('');
            $table->string('navLink', 300)->nullable()->default('');
            $table->integer('externalLink')->nullable(false)->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable()->default(1);
            $table->timestamps();

            $table->index(['parent_id'], 'fk_parent_id_wsabil_menus_idx');
            $table->foreign('role_id', 'fk_role_id_wabil_idx')
                ->references('id')->on(Role::getClassName())
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->foreign('status_id', 'fk_status_id_wabil_menu_idx')
                ->references('id')->on(Role::getClassName())
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Menu::getClassName());
    }
};
