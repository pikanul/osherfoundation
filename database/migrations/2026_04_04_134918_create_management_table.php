<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('management', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 125);
			$table->string('slug', 125)->nullable();
			$table->string('image', 125)->nullable();
			$table->string('designation', 125);
			$table->text('description')->nullable();
			$table->string('address', 125)->nullable();
			$table->string('phone', 125)->nullable();
			$table->string('email', 125)->nullable();
			$table->string('type', 125)->nullable();
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
		Schema::drop('management');
	}

}
