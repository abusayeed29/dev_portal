<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 10)->unique();
            $table->string('employee_name', 100);
            $table->string('company_id', 10);
            $table->string('department_id', 100)->nullable();
            $table->string('designation_id', 100)->nullable();
            $table->string('place_of_work', 100)->nullable();
            $table->string('project_id', 10)->nullable();
            $table->date('joining_date')->nullable();
            $table->integer('gross')->nullable();
            $table->string('employee_status', 2)->nullable();
            $table->date('confirm_date')->nullable();
            $table->string('bank_accno', 50)->nullable();
            $table->string('emp_management_staff', 3)->nullable();
            $table->string('sex', 1)->nullable();
            $table->string('father_name', 100)->nullable();
            $table->string('mother_name', 100)->nullable();
            $table->string('marital_status', 50)->nullable();
            $table->string('blood_group', 100)->nullable();
            $table->string('management_s', 50)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('pabx')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
