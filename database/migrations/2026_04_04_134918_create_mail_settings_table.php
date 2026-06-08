<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mail_settings', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('from_address', 125)->nullable();
			$table->string('from_name', 125)->nullable();
			$table->string('smtp_encryption', 125)->nullable();
			$table->string('smtp_host', 125)->nullable();
			$table->string('smtp_password', 125)->nullable();
			$table->string('smtp_port', 125)->nullable();
			$table->string('imap_port', 125)->nullable();
			$table->string('branch_id', 125)->nullable();
			$table->string('smtp_username', 125)->nullable();
			$table->string('status', 125)->nullable();
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
		Schema::drop('mail_settings');
	}

}
