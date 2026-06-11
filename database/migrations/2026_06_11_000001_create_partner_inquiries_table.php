<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('organization_type');
            $table->string('country');
            $table->text('address')->nullable();
            $table->string('website_url')->nullable();
            $table->string('contact_name');
            $table->string('designation');
            $table->string('email');
            $table->string('phone');
            $table->json('partnership_interests');
            $table->text('partnership_idea');
            $table->json('collaboration_types');
            $table->string('target_sector')->nullable();
            $table->string('geographic_area')->nullable();
            $table->string('expected_timeline')->nullable();
            $table->unsignedBigInteger('document_upload_id')->nullable();
            $table->boolean('accuracy_consent')->default(false);
            $table->boolean('processing_consent')->default(false);
            $table->boolean('read_status')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_inquiries');
    }
};
