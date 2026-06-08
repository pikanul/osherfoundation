<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('software_id')->constrained('softwares')->cascadeOnDelete();
            $table->foreignId('version_id')->nullable()->constrained('versions')->nullOnDelete();
            $table->string('license_key');
            $table->string('device_id')->nullable();
            $table->string('domain')->nullable();
            $table->string('plan_name')->nullable();
            $table->string('status')->default('active');
            $table->string('billing_cycle')->default('monthly');
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('grace_days')->default(0);
            $table->boolean('auto_renew')->default(true);
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('last_api_check_at')->nullable();
            $table->timestamp('last_update_at')->nullable();
            $table->timestamps();

            $table->index(['software_id', 'license_key'], 'subscriptions_software_license_idx');
            $table->unique(['software_id', 'license_key'], 'subscriptions_software_license_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
