<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->string('topic');
            $table->string('priority');
            $table->string('status');
            $table->boolean('l2')->default(false);
            $table->date('date_arrived');
            $table->time('time_arrived');
            $table->date('date_answered')->nullable();
            $table->time('time_answered')->nullable();
            $table->boolean('assigned_to_me')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
