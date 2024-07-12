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
        Schema::create('exam_group_map', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_group_id');
            $table->unsignedBigInteger('exam_id');
            $table->timestamps();

            $table->foreign('exam_group_id')->references('id')->on('exam_group')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_group_map');
    }
};
