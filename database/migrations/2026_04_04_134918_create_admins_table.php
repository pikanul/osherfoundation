<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name', 125);
			$table->string('email', 125)->unique();
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 125);
			$table->string('mobile', 125)->nullable()->unique();
			$table->string('profile_image', 125)->nullable();
			$table->enum('is_active', array('1','0'))->default('1')->comment('1=Active, 0=Inactive');
			$table->string('remember_token', 100)->nullable();
			$table->softDeletes();
			$table->timestamps(6);
			$table->string('degination', 200)->nullable();
			$table->text('permissions')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admins');
	}

}
