<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->unique();
            $table->timestamps();

        });

        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->unique();
            $table->timestamps();
        });

        Schema::create('ability', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->unique();
            $table->timestamps();
        });

        Schema::create('users_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->timestamp('created_at');

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('roles_abilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('ability_id');
            $table->timestamp('created_at');

            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
            $table->foreign('ability_id')->references('id')->on('ability')->onDelete('cascade');
            $table->unique(['role_id', 'ability_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_abilities');
        Schema::dropIfExists('users_roles');
        Schema::dropIfExists('ability');
        Schema::dropIfExists('role');
        Schema::dropIfExists('user');
    }
}
