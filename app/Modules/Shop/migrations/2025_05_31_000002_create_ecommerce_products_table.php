
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceProductsTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'ecommerce_products';

	/**
	 * Run the migrations.
	 * @table users
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('total_sales')->default(0);
            $table->string('unit')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount')->nullable();
            $table->integer('quantity')->nullable();
            $table->boolean('active')->default(1);

			$table->index(['sku','slug']);
			$table->index(['created_by'], 'fk_created_by_users_idx2');
			$table->foreign('created_by', 'fk_created_by_users_idx2')
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
