<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePatientsTableAddImageAndHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Add the column only if it does not already exist
            if (!Schema::hasColumn('patients', 'medical_history')) {
                $table->text('medical_history')->nullable();
            }

            // You can also add other columns like image if needed
            if (!Schema::hasColumn('patients', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('patients', 'medical_history')) {
                $table->dropColumn('medical_history');
            }
            if (Schema::hasColumn('patients', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
}
