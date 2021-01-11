<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('king_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('king_id');
            $table->string('ip');
            $table->string('jwt');
            $table->unsignedInteger('logged_as');
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
        Schema::dropIfExists('king_logs');
    }
}
