<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAstHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ast_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_id');
            $table->integer('ast_user_id');
            $table->string('ast_status');
            $table->integer('wh_assign_id');
            $table->timestamp('assign_date')->nullable();
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
        Schema::dropIfExists('ast_histories');
    }
}
