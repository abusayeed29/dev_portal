<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalHeirarchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_heirarchies', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('stage');
            $table->integer('user_id');
            $table->integer('dept_id')->unsigned()->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->tinyInteger('module_id')->nullable();
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
        Schema::dropIfExists('approval_heirarchies');
    }
}
