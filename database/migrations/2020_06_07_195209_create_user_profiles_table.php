<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->char('nickname', 50)->nullable();
            $table->char('cep', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->char('number_address', 50)->nullable();
            $table->char('cpf', 15)->nullable();
            $table->char('rg', 50)->nullable();
            $table->char('whatsapp', 50)->nullable();
            $table->char('telephone', 50)->nullable();
            $table->string('path', 250)->nullable();
            $table->string('photo', 250)->nullable();
            $table->string('biography', 250)->nullable();
            $table->char('linkedin', 100)->nullable();
            $table->char('facebook', 100)->nullable();
            $table->char('instagram', 100)->nullable();
            $table->char('twitter', 100)->nullable();
            $table->char('youtube', 100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
