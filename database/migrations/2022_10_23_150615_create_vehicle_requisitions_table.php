<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('vehicle_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('pick_from', 50);
            $table->string('drop_from', 50);
            $table->timestamp('requi_date');
            $table->string('pick_time', 10);
            $table->string('drop_time', 10);
            $table->tinyInteger('stage')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->string('description')->nullable();
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('driver_id')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('vehicle_requisitions');
    }
}
