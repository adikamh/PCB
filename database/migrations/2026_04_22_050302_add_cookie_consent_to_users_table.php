<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cookie_consent')) {
                $table->boolean('cookie_consent')->default(false)->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'cookie_consent_at')) {
                $table->timestamp('cookie_consent_at')->nullable()->after('cookie_consent');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cookie_consent', 'cookie_consent_at']);
        });
    }
};