<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceProductsEvaluationsTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'ecommerce_products_evaluations';

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
			$table->unsignedBigInteger('product_id')->nullable();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->integer('score')->nullable();
			$table->string('commentary',250)->nullable();
			$table->string('name',50)->nullable();
			$table->string('email',70)->nullable();

			$table->index(['product_id'], 'fk_product_id_products_evaluations_idx');
			$table->foreign('product_id', 'fk_product_id_products_evaluations_idx')
				->references('id')->on('ecommerce_products')
				->onDelete('no action')
				->onUpdate('no action');

			$table->foreign('created_by', 'fk_created_by_users_evaluations_idx2')
				->references('id')->on('users')
				->onDelete('no action')
				->onUpdate('no action');

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
