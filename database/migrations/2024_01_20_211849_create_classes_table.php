<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('class_name', 255)->unique(); // Unique class name
            $table->unsignedInteger('grade_level')->default(1); // Default grade level to 1
            $table->string('section', 255)->nullable(); // Optional section
            $table->unsignedBigInteger('teacher_id'); // Foreign key to Teachers table
            $table->unsignedBigInteger('academic_year_id'); // Foreign key to AcademicYears table
            $table->unsignedInteger('total_students')->nullable(); // Optional total students
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
};
