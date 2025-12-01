<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bexio_country_id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('name_short')->nullable(false);
            $table->string('iso3166_alpha2', 2);
            $table->boolean('default')->default(false);
            $table->timestamps();

            $table->unique('bexio_country_id');
            $table->unique('name');
            $table->unique('name_short');
            $table->unique('iso3166_alpha2');

            $table->index('bexio_country_id');
            $table->index('name');
            $table->index('name_short');
            $table->index('iso3166_alpha2');
            $table->index('default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropUnique(['bexio_country_id']);
            $table->dropUnique(['name']);
            $table->dropUnique(['name_short']);
            $table->dropUnique(['iso3166_alpha2']);

            $table->dropIndex(['bexio_country_id']);
            $table->dropIndex(['name']);
            $table->dropIndex(['name_short']);
            $table->dropIndex(['iso3166_alpha2']);
            $table->dropIndex(['default']);
        });

        Schema::dropIfExists('countries');
    }
}
