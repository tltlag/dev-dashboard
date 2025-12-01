<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extension_id');
            $table->unsignedBigInteger('user_id');
            $table->string('start');
            $table->string('end');
            $table->string('src');
            $table->string('dst');
            $table->string('from_number');
            $table->string('to_number');
            $table->string('to_name');
            $table->string('from_name');
            $table->string('billsec');
            $table->string('lastapp');
            $table->string('dest_type');
            $table->string('disposition');
            $table->text('wildixin_response');
            $table->timestamps();

            $table->unique(['extension_id', 'user_id', 'start']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_histories');
    }
}
