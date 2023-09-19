<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_id');
            $table->integer('user_id');
            $table->integer('tkt_type_id')->nullable();
            $table->integer('company_id');
            $table->enum('priority' ,  array('None','Normal', 'Low' ,'High'));
            // $table->enum('status', array('new','open', 'ongoing', 'completed'));
            $table->tinyInteger('tkt_status_id');
            $table->string('description',2000);
            $table->string('feedback')->nullable();
            $table->integer('supporter_uid')->nullable();
            $table->integer('assigner_uid')->nullable();
            $table->timestamp('assign_time')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
