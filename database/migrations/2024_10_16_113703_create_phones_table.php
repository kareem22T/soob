<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique(); // Unique phone number
            $table->timestamp('verified_at')->nullable(); // Nullable verification timestamp
            $table->string('verification_code')->nullable(); // Nullable verification code (to be hashed)
            $table->timestamp('current_code_expired_at')->nullable(); // Expiration time for the verification code
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
        Schema::dropIfExists('phones');
    }
};
