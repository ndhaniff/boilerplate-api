<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_group_members', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('access_group_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('access_group_id')->references('id')->on('access_groups');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_group_members');
    }
}
