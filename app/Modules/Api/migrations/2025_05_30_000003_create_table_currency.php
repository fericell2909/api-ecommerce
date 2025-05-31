<?php

use App\Modules\Api\Models\Currency;
use App\Modules\Api\Models\Status;
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
        Schema::create(Currency::TABLE, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->string('color', 50)->nullable(false)->default('bg-success');
            $table->string('codigo_sii', 50)->nullable(false)->default('');
            $table->string('symbol', '')->nullable(false)->default('');
            $table->unsignedBigInteger('status_id')->nullable()->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('status_id', 'fk_status_id_wa_currencies_idx')
                ->references('id')->on(Status::TABLE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Currency::TABLE);
    }
};
