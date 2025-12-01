<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBexioEmployeeHasCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bexio_employee_has_companies', function (Blueprint $table) {
            $table->id();
              $table->unsignedBigInteger('bexio_employee_id');
            $table->unsignedBigInteger('bexio_company_id');
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
        Schema::dropIfExists('bexio_employee_has_companies');
    }
}
