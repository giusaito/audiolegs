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
            $table->string('name', 255);
            $table->timestamps();
        });
        Schema::create('law_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('law_id')->unsigned();
            $table->text('name', 255);
            $table->enum('type', ['FILE', 'FOLDER'])->default('FILE');
            $table->nestedSet();
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
