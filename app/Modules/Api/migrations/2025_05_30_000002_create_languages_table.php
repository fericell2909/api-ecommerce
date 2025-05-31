<?php

use App\Modules\Api\Models\Language;
use Database\Seeders\traits\ClassDetector;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use ClassDetector;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Language::getClassName(), function (Blueprint $table) {
            $table->id();
            $table->string('alias', 3)->unique()->nullable(false);
            $table->string('country_code', 3)->unique()->nullable(false);
            $table->text('description')->nullable(true);
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
        Schema::dropIfExists(Language::getClassName());
    }
};
