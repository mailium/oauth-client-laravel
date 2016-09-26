<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailium_app_users', function (Blueprint $table) {

            # -------------------------------------------------------------------------------------------------- Columns
            $table->increments('id');
            $table->string('accid');
            $table->text('oauth_tokens');
            $table->timestamps();

            # -------------------------------------------------------------------------------------------------- Indexes
            //

            # ------------------------------------------------------------------------------------------- Unique Indexes
            $table->unique('accid');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mailium_app_users');
    }
}
