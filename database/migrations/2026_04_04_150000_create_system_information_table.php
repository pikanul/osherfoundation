<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('system_information', function (Blueprint $table) {
            $table->id();
            $table->string('license_key')->nullable();
            $table->string('license_type')->nullable();
            $table->string('license_status')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('app_version')->nullable();
            $table->string('system_build_number')->nullable();
            $table->string('release_channel')->nullable();
            $table->text('last_update_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_information');
    }
};

