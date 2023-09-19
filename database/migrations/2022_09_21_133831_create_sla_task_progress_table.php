<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaTaskProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sla_task_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('task_cat_id');
            $table->integer('task_id');
            // $table->enum('overall_status', ['started','completed']);
            $table->timestamp('started_at');
            $table->timestamp('completed_at');
            $table->smallInteger('status_percent');
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
        Schema::dropIfExists('sla_task_progress');
    }
}
