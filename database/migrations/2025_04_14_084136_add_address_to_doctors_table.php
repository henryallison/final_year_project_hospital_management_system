<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_address_to_doctors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Add the address column
            $table->text('address')->nullable(); // Make it nullable as it's not mandatory
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
            // Drop the address column if we roll back the migration
            $table->dropColumn('address');
        });
    }
}

