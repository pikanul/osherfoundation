<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendar_events', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('title');
			$table->bigInteger('type_id')->default(0);
			$table->text('description')->nullable();
			$table->time('start_time')->nullable();
			$table->time('end_time')->nullable();
			$table->date('start_date');
			$table->date('end_date');
			$table->boolean('status')->nullable()->default(1);
			$table->timestamps(6);
			$table->boolean('visibility')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('calendar_events');
	}

}
