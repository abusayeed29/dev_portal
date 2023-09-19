<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ast_components', function (Blueprint $table) {
            $table->id();
            $table->string('cmpnt_id', 20);
            $table->string('cmpnt_name', 100);
            $table->string('brand', 100)->nullable();
            $table->string('supplier', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('warranty', 100)->nullable();
            $table->string('value', 100)->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->integer('user_id');
            $table->tinyInteger('company_id');
            $table->enum('status' ,  array('pending','approved', 'deployed' ,'damaged'));
            $table->string('description', 300)->nullable();
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
        Schema::dropIfExists('ast_components');
    }
}
