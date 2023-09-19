<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_rosters', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('title', 50)->nullable();
            $table->integer('inserted_uid');
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
        Schema::dropIfExists('event_rosters');
    }
}
