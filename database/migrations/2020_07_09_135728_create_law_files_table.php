<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLawFilesTable extends Migration
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
            $table->nestedSet();
            $table->timestamps();
        });
        Schema::create('law_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('law_id')->unsigned();
            $table->string('name', 100);
            $table->text('description');
            $table->string('path', 255);
            $table->string('type', 255);
            $table->timestamps();

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
        Schema::dropIfExists('laws');
        Schema::dropIfExists('law_files');
    }
}
