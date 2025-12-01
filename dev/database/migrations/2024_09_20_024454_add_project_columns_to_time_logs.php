<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectColumnsToTimeLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('clockodo_project_id')->nullable()->after('client_name');
            $table->string('clockodo_project_name')->nullable()->after('clockodo_project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->dropColumn('clockodo_project_id');
            $table->dropColumn('clockodo_project_name');
        });
    }
}
