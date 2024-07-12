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
        Schema::create('exam_group', function (Blueprint $table) {
            $table->id();
            $table->string('exam_group_name')->unique();
            $table->string('sheet_title')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('grade_level')->default(1); // Default grade level to 1
            $table->unsignedBigInteger('exam_type_id')->nullable();
            $table->timestamps();

            // Foreign key constraint (if using exam_types table)
            $table->foreign('exam_type_id')->references('id')->on('exam_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_group');
    }
};