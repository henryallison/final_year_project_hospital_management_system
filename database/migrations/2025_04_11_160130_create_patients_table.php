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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('contact_number', 20);
            $table->text('address');

            // Medical information
            $table->text('medical_history');
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();

            // Encrypted sensitive data
            $table->text('encrypted_data');

            // Staff relationships
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nurse_id')->nullable()->constrained('users')->onDelete('set null');

            // Status tracking
            $table->enum('status', ['active', 'discharged', 'transferred'])->default('active');
            $table->date('admission_date')->nullable();
            $table->date('discharge_date')->nullable();

            $table->timestamps();
            $table->softDeletes(); // This adds the deleted_at column
        });

        // Add index for better performance on soft deletes
        Schema::table('patients', function (Blueprint $table) {
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // First drop foreign keys to avoid errors
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['nurse_id']);
        });

        Schema::dropIfExists('patients');
    }
};
