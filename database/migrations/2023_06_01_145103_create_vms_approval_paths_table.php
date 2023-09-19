<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVmsApprovalPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('vms_approval_paths', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('stage');
            $table->integer('team_id')->unsigned()->nullable();
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
        Schema::dropIfExists('vms_approval_paths');
    }
}
