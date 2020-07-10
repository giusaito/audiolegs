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
            $table->enum('type', ['file', 'folder'])->default('folder');
            $table->string('path',100);
            $table->bigInteger('size')->unsigned();
            $table->nestedSet();
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
        Schema::dropIfExists('laws');
    }
}
