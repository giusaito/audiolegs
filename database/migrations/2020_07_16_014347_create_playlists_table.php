<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->string('cover_image', 191)->nullable();
            $table->enum('status', ['PUBLIC', 'PRIVATE'])->default('PUBLIC');
            $table->enum('type', ['ADMIN', 'USER'])->default('USER');
            $table->bigInteger('author_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('author_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });
        Schema::create('law_playlist', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('playlist_id')->unsigned();
            $table->bigInteger('law_id')->unsigned();
            $table->foreign('playlist_id')
            ->references('id')
            ->on('playlists')
            ->onDelete('cascade');
            $table->foreign('law_id')
            ->references('id')
            ->on('laws')
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
        Schema::dropIfExists('playlists');
        Schema::dropIfExists('playlist_law');
    }
}
