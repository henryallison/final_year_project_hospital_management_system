<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_contact_number_to_doctors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactNumberToDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Add the contact_number column
            $table->string('contact_number')->nullable(); // Adding 'contact_number' as a string and making it nullable
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
            // Remove the contact_number column in case we want to roll back the migration
            $table->dropColumn('contact_number');
        });
    }
}
