<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class EditorialMembersSchemaMigration extends Migration 
{
    public function up()
    {
        Capsule::schema()->create('editorial_members', function (Blueprint $table) {
            $table->bigInteger('editorial_member_id')->autoIncrement();
            $table->string('path', 255);
            $table->bigInteger('context_id');
        });

        // Editorial member settings
        Capsule::schema()->create('editorial_member_settings', function(Blueprint $table) {
            $table->bigInteger('editorial_member_id');
            $table->string('locale', 14)->default('');
            $table->string('setting_name', 255);
            $table->longText('setting_value')->nullable();
            $table->string('setting_type', 6)->comment('(bool|int|float|string|object)');
            $table->index(['editorial_member_id'], 'editorial_member_settings_editorial_member_id');
            $table->unique(['editorial_member_id', 'locale', 'setting_name'], 'editorial_member_settings_pkey');
        });
    }
}