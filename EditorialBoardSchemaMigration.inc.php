<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class EditorialBoardSchemaMigration extends Migration {
        /**
         * Run the migrations.
         * @return void
         */
    public function up() {
		Capsule::schema()->create('editorial_board', function (Blueprint $table) {
			$table->bigInteger('editorial_member_id')->autoIncrement();
			$table->string('path', 255);
			$table->bigInteger('context_id');
		});

		Capsule::schema()->create('editorial_member_settings', function (Blueprint $table) {
			$table->bigInteger('editorial_member_id');
			$table->string('locale', 14)->default('');
			$table->string('setting_name', 255);
			$table->longText('setting_value')->nullable();
			$table->string('setting_type', 6)->comment('(bool|int|float|string|object)');
			$table->index(['editorial_member_id'], 'editorial_member_settings_editorial_member_id');
			$table->unique(['editsvchemaorial_member_id', 'locale', 'setting_name'], 'editorial_member_settings_pkey');
		});

	}

    public function check() 
    {
		return Capsule::schema()->hasTable('editorial_board');
	}
}