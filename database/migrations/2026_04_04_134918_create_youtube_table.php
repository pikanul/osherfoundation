<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('youtube', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('upload_id', 125)->nullable();
			$table->string('video_url', 125);
			$table->string('title', 125)->nullable();
			$table->timestamps(6);
			$table->text('description')->nullable();
			$table->boolean('status')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('youtube');
	}

}
