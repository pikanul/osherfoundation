<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('software_id')->constrained('softwares')->cascadeOnDelete();
            $table->string('version');
            $table->date('release_date')->nullable();
            $table->text('changelog')->nullable();
            $table->string('file_path')->nullable();
            $table->string('update_url')->nullable();
            $table->longText('update_sql')->nullable();
            $table->longText('environment_commands')->nullable();
            $table->string('hash', 128)->nullable();
            $table->boolean('force_update')->default(false);
            $table->boolean('is_stable')->default(true);
            $table->timestamps();

            $table->unique(['software_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};
