<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blogs', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('title', 125);
			$table->string('slug', 125)->nullable();
			$table->string('upload_id', 125)->nullable();
			$table->bigInteger('attachment_id')->default(0);
			$table->text('short_description')->nullable();
			$table->text('description')->nullable();
			$table->date('publish_date')->nullable();
			$table->bigInteger('category_id')->unsigned()->nullable()->index('blogs_category_id_foreign');
			$table->bigInteger('user_id')->unsigned()->nullable()->index('blogs_user_id_foreign');
			$table->enum('status', array('active','inactive'))->default('active');
			$table->timestamps(6);
			$table->integer('sub_category_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blogs');
	}

}
