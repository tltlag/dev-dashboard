<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTimeLogsTableUpdateWildixinIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            if (Schema::hasColumn('time_logs', 'wildixin_id')) {
                $table->renameColumn('wildixin_id', 'call_history_id');
            }
        });

        Schema::table('time_logs', function (Blueprint $table) {
            if (Schema::hasColumn('time_logs', 'call_history_id')) {
                $table->unsignedBigInteger('call_history_id')->nullable()->change();

                $indexName = 'time_logs_call_history_id_index';
                $indexExists = DB::select(DB::raw("SHOW INDEX FROM time_logs WHERE Key_name = '{$indexName}'"));

                if (!$indexExists) {
                    $table->index(['call_history_id']);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('time_logs', function (Blueprint $table) {
        //     if (Schema::hasColumn('time_logs', 'call_history_id')) {
        //         $table->dropIndex(['call_history_id']);
        //         $table->dropColumn('call_history_id');
        //     }
        // });
    }
}
