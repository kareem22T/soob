<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromYourTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            // Check and drop each column if it exists
            if (Schema::hasColumn('companies', 'is_phone_verified')) {
                $table->dropColumn('is_phone_verified');
            }
            if (Schema::hasColumn('companies', 'is_approved')) {
                $table->dropColumn('is_approved');
            }
            if (Schema::hasColumn('companies', 'password')) {
                $table->dropColumn('password');
            }
            if (Schema::hasColumn('companies', 'verification_code')) {
                $table->dropColumn('verification_code');
            }
            if (Schema::hasColumn('companies', 'current_code_expired_at')) {
                $table->dropColumn('current_code_expired_at');
            }
            if (Schema::hasColumn('companies', 'is_rejected')) {
                $table->dropColumn('is_rejected');
            }
            if (Schema::hasColumn('companies', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            // Check and add columns back if they do not exist
            if (!Schema::hasColumn('companies', 'is_phone_verified')) {
                $table->boolean('is_phone_verified')->default(false);
            }
            if (!Schema::hasColumn('companies', 'is_approved')) {
                $table->boolean('is_approved')->default(false);
            }
            if (!Schema::hasColumn('companies', 'password')) {
                $table->string('password'); // Specify length if needed
            }
            if (!Schema::hasColumn('companies', 'verification_code')) {
                $table->string('verification_code');
            }
            if (!Schema::hasColumn('companies', 'current_code_expired_at')) {
                $table->timestamp('current_code_expired_at')->nullable();
            }
            if (!Schema::hasColumn('companies', 'is_rejected')) {
                $table->boolean('is_rejected')->default(false);
            }
            if (!Schema::hasColumn('companies', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }
}
