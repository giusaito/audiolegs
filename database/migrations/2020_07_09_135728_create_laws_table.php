<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laws', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['file', 'folder'])->default('folder');
            $table->string('path',100);
            $table->bigInteger('size')->unsigned();
            $table->string('audio_name', 255)->nullable();
            $table->string('audio_description', 255)->nullable();
            $table->text('audio_text')->nullable();
            $table->string('audio_narrator', 255)->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('updated_user_id' )->unsigned()->nullable();
            $table->nestedSet();
            $table->timestamp('text_updated', 0)->nullable();	
            $table->timestamp('audio_updated', 0)->nullable();
            $table->timestamps();


            $table->foreign('user_id')
            ->references('id')
            ->on('users');
            
            $table->foreign('updated_user_id')
            ->references('id')
            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laws');
    }
}
