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
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'is_approved')) {
                $table->dropColumn('is_approved');
            }
            if (Schema::hasColumn('employees', 'is_rejected')) {
                $table->dropColumn('is_rejected');
            }
            if (Schema::hasColumn('employees', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
