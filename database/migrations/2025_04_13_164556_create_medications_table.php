<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('medications', function (Blueprint $table) {
        $table->id();

        // Foreign key to the patients table
        $table->unsignedBigInteger('patient_id');

        // Medication details
        $table->string('name');                  // e.g. Paracetamol
        $table->string('dosage');                // e.g. 500mg
        $table->string('frequency');             // e.g. Once a day
        $table->date('start_date')->nullable();
        $table->date('due_date')->nullable();    // This is what your controller looks for

        $table->timestamps();

        // Foreign key constraint
        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
