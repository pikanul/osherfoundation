<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscribers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 150)->nullable();
			$table->string('phone', 20)->nullable()->unique('phone');
			$table->string('email', 150)->nullable()->unique('email');
			$table->boolean('status')->nullable()->default(1)->comment('1=active, 0=inactive, 2=unsubscribed');
			$table->timestamp('subscribed_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('unsubscribed_at')->nullable();
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
		Schema::drop('subscribers');
	}

}
