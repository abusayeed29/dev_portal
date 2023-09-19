<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('ast_name', 100);
            $table->tinyInteger('company_id');
            $table->tinyInteger('ast_type');
            $table->string('ast_tag', 40)->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('supplier', 100)->nullable();
            $table->string('condition', 100)->nullable();
            $table->string('warranty', 100)->nullable();
            $table->string('value', 100)->nullable();
            $table->integer('user_id');
            $table->string('description', 100)->nullable();
            $table->enum('status' ,  array('pending','approved', 'deployed' ,'damaged'));
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
        Schema::dropIfExists('assets');
    }
}
