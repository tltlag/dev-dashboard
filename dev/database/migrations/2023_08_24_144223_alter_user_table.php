<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->after('email');
            $table->string('username')->unique()->after('phone');
            $table->string('profile_image')->nullable(true)->after('username');
            $table->date('dob')->nullable(true)->after('name');
            $table->string('sex')->nullable(true)->comment('M => Male, F => Female')->after('dob');
            $table->integer('role')->nullable(false)->default(20)->comment('1 => Employee')->after('remember_token');
            $table->boolean('status')->nullable(false)->default(0)->comment('0 => In-active, 1 => Active')->after('role');
            $table->timestamp('last_login')->nullable(true)->after('status');
            $table->integer('approved')
                ->nullable(false)
                ->default(0)
                ->comment('0 => Pending, 10 => Approved, 20 => Rejected')
                ->after('last_login');

            $table->index('phone');
            $table->index('username');
            $table->index('role');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_username_unique');
            $table->dropIndex('users_phone_unique');
            $table->dropIndex('users_username_index');
            $table->dropIndex('users_phone_index');
            $table->dropIndex('users_role_index');
            $table->dropColumn([
                'username',
                'dob',
                'sex',
                'role',
                'status',
                'last_login',
                'approved',
            ]);
        });
    }
}
