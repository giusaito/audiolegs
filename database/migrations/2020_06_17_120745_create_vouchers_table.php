<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('chave', 255);
            $table->decimal('desconto',10,2)->nullable();
            $table->bigInteger('desconto_porcentagem')->nullable()->unsigned();
            $table->bigInteger('quantidade_total')->nullable()->unsigned();
            $table->bigInteger('quantidade_usado')->nullable()->unsigned();
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_fim')->nullable();
            $table->enum('status', ['PUBLISHED', 'UNPUBLISHED'])->default('PUBLISHED');
            $table->timestamps();
        });
        Schema::create('voucher_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('voucher_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('voucher_id')
            ->references('id')
            ->on('vouchers')
            ->onDelete('cascade');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });
        Schema::create('plan_voucher', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_id')->unsigned();
            $table->bigInteger('voucher_id')->unsigned();
            $table->foreign('plan_id')
            ->references('id')
            ->on('plans')
            ->onDelete('cascade');
            $table->foreign('voucher_id')
            ->references('id')
            ->on('vouchers')
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
        Schema::dropIfExists('vouchers');
    }
}
