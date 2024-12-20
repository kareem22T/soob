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
        Schema::table('user_settings', function (Blueprint $table) {
            $table->string('company_ad_title')->nullable()->change();
            $table->string('company_ad_thumbnail')->nullable()->change();
            $table->text('company_ad_description')->nullable()->change();
            $table->string('company_ad_action_btn')->nullable()->change();
            $table->boolean('toggle_company_ad_section')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            //
        });
    }
};
