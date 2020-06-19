<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('fantasy_name', 255);
            $table->string('social_reason', 255);
            $table->string('nickname', 255);
            $table->char('cnpj', 50);
            $table->char('telephone', 50);
            $table->char('email', 50);
            $table->char('rector', 50);
            $table->bigInteger('state_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('state_id')
                        ->references('id')
                        ->on('states');

            $table->foreign('city_id')
                        ->references('id')
                        ->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('universities');
    }
}
