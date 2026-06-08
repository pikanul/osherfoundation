<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('title', 125);
			$table->date('publish_date');
			$table->string('news_image', 125)->nullable();
			$table->string('slug', 125);
			$table->text('short_descripiton')->nullable();
			$table->text('long_description')->nullable();
			$table->bigInteger('news_category_id')->unsigned()->nullable()->index('news_news_category_id_foreign');
			$table->timestamps(6);

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news');
	}

}
