<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactRelationIdFieldInBexioEmployeeHasCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bexio_employee_has_companies', function (Blueprint $table) {
            $table->bigInteger('contact_relation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bexio_employee_has_companies', function (Blueprint $table) {
            $table->dropColumn('contact_relation_id');
        });
    }
}
