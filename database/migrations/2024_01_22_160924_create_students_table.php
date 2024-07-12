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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth')->nullable(false);
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable(false);
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable(false)->unique();
            $table->string('email_address')->unique()->nullable(false);
            $table->integer('grade_level')->nullable(false);
            $table->unsignedBigInteger('class_id')->nullable(false);
            $table->foreign('class_id')->references('id')->on('classes');
            $table->date('admission_date')->nullable(false);
            $table->enum('status', ['Active', 'Inactive', 'Graduated'])->nullable(false);
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
