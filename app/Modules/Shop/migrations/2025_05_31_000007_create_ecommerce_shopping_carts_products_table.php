<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceShoppingCartsProductsTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'ecommerce_shopping_carts_products';

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
			$table->unsignedBigInteger('cart_id')->nullable();
			$table->unsignedBigInteger('product_id')->nullable();
			$table->integer('product_quantity')->nullable();

			$table->index(['cart_id'], 'fk_cart_id_shopping_carts_idx');
			$table->foreign('cart_id', 'fk_cart_id_shopping_carts_idx')
				->references('id')->on('ecommerce_shopping_carts')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['product_id'], 'fk_product_id_products_idx');
			$table->foreign('product_id', 'fk_product_id_products_idx')
				->references('id')->on('ecommerce_products')
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
