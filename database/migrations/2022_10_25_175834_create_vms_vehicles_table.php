<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVmsVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('vms_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_name', 100);
            $table->string('vin_sn');
            $table->string('registration_date');
            $table->string('al_cell_no');
            $table->string('al_email');
            $table->string('ownership');
            $table->string('vehicle_type');
            $table->string('vehicle_division');
            $table->string('brta_office');
            $table->string('driver');
            $table->string('seat_capicity');
            $table->string('company');
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
        Schema::dropIfExists('vms_vehicles');
    }
}
