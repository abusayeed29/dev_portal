<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstComponentLooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ast_component_looks', function (Blueprint $table) {
            $table->id();
            $table->string('ctype_name');
            $table->integer('parent_id');
            $table->integer('sub_parent_id');
            $table->tinyInteger('company_id');
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
        Schema::dropIfExists('ast_component_looks');
    }
}
