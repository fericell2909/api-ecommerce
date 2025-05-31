<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceCategoriesTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'ecommerce_categories';

	/**
	 * Run the migrations.
	 * @table users
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('parent_id')->nullable();
			$table->integer('order')->nullable();
			$table->string('icon', 50)->nullable();
			$table->string('name', 200);
			$table->string('slug', 200);

			$table->unique(["slug"], 'slug_UNIQUE');

			$table->index(['created_by'], 'fk_categories_users_idx');
			$table->foreign('created_by', 'fk_categories_users_idx')
				->references('id')->on('users')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['parent_id'], 'fk_parent_id_shopping_carts_idx');

			$table->dateTime('created_at')->nullable();
			$table->dateTime('updated_at')->nullable();
			$table->dateTime('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists($this->tableName);
	}
}
