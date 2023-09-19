<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sla_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 120);
            $table->integer('cat_id');
            $table->tinyInteger('status_id')->default(1);
            $table->string('public_token', 24)->unique();
            $table->text('body',500);
            $table->integer('user_id');
            $table->unsignedInteger('team_id');
            $table->integer('company_id');
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sla_tasks');
    }
}
