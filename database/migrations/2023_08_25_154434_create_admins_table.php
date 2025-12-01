<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('profile_image')->nullable(true);
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->integer('role')->nullable(false)->default(2)->comment('1 => Super Admin, 2 => Admin');
            $table->boolean('status')->nullable(false)->default(0)->comment('0 => In-active, 1 => Active');
            $table->timestamp('last_login')->nullable(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
            $table->index('username');
            $table->index('email');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
