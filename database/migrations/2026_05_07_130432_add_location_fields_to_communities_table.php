<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->string('country', 2)->nullable()->after('club_id')->comment('ISO country code');
            $table->string('state', 100)->nullable()->after('country');
            $table->string('region', 100)->nullable()->after('state');
            $table->index('country');
            $table->index('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->dropIndex(['country']);
            $table->dropIndex(['state']);
            $table->dropColumn(['country', 'state', 'region']);
        });
    }
};
