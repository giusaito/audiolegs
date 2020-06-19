<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('university_id')->unsigned();
            $table->bigInteger('state_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('plan_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('university_id')
                        ->references('id')
                        ->on('universities');
            $table->foreign('state_id')
                        ->references('id')
                        ->on('states');
            $table->foreign('city_id')
                        ->references('id')
                        ->on('cities');
            $table->foreign('plan_id')
                        ->references('id')
                        ->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}