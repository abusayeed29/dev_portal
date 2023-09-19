<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaTaskCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sla_task_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lead_time', 4);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('user_id');
            $table->tinyInteger('department_id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('color');
            $table->softDeletes();
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
        Schema::dropIfExists('sla_task_categories');
    }
}
