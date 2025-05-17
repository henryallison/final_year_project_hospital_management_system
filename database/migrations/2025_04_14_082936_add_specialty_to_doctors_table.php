<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_specialty_to_doctors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialtyToDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Add the specialty column
            $table->string('specialty')->nullable(); // This adds the 'specialty' column as a string and makes it nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Remove the specialty column in case we want to roll back the migration
            $table->dropColumn('specialty');
        });
    }
}
