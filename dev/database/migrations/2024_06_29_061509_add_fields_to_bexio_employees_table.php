<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBexioEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bexio_employees', function (Blueprint $table) {
            $table->string('email')->nullable()->after('fax_number');
            $table->string('city')->nullable()->after('email');
            $table->string('postal_code')->nullable()->after('city');
            $table->bigInteger('bexio_country_id')->nullable()->after('postal_code');
            $table->bigInteger('clockodo_emp_id')->nullable()->after('bexio_country_id');
            $table->text('bexio_response')->nullable()->after('bexio_country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bexio_employees', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('city');
            $table->dropColumn('postal_code');
            $table->dropColumn('bexio_country_id'); 
            $table->dropColumn('bexio_response');
            if (Schema::hasColumn('bexio_employees', 'clockodo_emp_id')) {
                $table->dropColumn('clockodo_emp_id');
            }
            if (Schema::hasColumn('bexio_employees', 'clockodo_response')) {
                $table->dropColumn('clockodo_response');
            }

        });
    }
}
