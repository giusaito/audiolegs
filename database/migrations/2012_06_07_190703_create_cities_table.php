<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('state_id')->unsigned();
            $table->string('title', 255);
            $table->bigInteger('iso');
            $table->bigInteger('iso_ddd');
            $table->bigInteger('status');
            $table->string('slug');
            $table->bigInteger('population');
            $table->decimal('lat', 12, 8);
            $table->decimal('long', 12, 8);
            $table->decimal('income_per_capita', 8, 2);
            $table->timestamps();

            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
