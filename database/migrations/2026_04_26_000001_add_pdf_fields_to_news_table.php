<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPdfFieldsToNewsTable extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'pdf_file_id')) {
                $table->unsignedBigInteger('pdf_file_id')->nullable()->after('news_image');
            }

            if (!Schema::hasColumn('news', 'use_pdf_after_cover')) {
                $table->boolean('use_pdf_after_cover')->default(false)->after('pdf_file_id');
            }
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'use_pdf_after_cover')) {
                $table->dropColumn('use_pdf_after_cover');
            }

            if (Schema::hasColumn('news', 'pdf_file_id')) {
                $table->dropColumn('pdf_file_id');
            }
        });
    }
}
