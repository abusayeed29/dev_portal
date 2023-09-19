<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVmsDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('vms_drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('mobile', 20);
            $table->string('national_id')->nullable();
            $table->string('timeslot')->nullable();
            $table->date('dob')->nullable();
            $table->string('present_address', 100)->nullable();
            $table->string('permanent_address', 100)->nullable();
            $table->string('license_number', 100)->nullable();
            $table->string('license_type', 100)->nullable();
            $table->date('license_issue_date')->nullable();
            $table->date('join_date')->nullable();
            $table->enum('leavestatus',[1,0])->default(1);
            $table->string('picture', 100)->nullable();
            $table->enum('is_active',[1,0])->default(1);
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
        Schema::dropIfExists('vms_drivers');
    }
}
