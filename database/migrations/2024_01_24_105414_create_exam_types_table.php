<?php
// In create_exam_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamTypesTable extends Migration
{
    public function up()
    {
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('exam_type_code')->unique();
            $table->string('exam_type_name'); // Add this line
            $table->string('description');
            $table->integer('pass_mark');
            $table->integer('total_mark');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_types');
    }
}
