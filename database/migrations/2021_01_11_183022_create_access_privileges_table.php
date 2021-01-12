<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessPrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_privileges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code'); 
            $table->string('name'); 
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('access_group_privileges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('access_group_id')->unsigned();
            $table->foreign('access_group_id')->references('id')->on('access_groups')->onDelete('cascade');
            $table->integer('access_privilege_id')->unsigned();
            $table->foreign('access_privilege_id')->references('id')->on('access_privileges')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_privileges');
        Schema::dropIfExists('access_group_privileges');
    }
}
