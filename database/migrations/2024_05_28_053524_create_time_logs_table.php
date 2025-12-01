<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wildixin_id')->unsigned()->nullable(false);
            $table->date('date');
            $table->integer('duration');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('client_id');
            $table->string('client_name');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->text('service_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_logs');
    }
}
