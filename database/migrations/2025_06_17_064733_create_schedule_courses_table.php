<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade'); //el constrained es para decir que se relaciona con la tabla users
            $table->string('course_code');
            $table->string('name');
            $table->string('creadits');
            $table->integer('group');
            $table->string('schedule_list');
            $table->string('format');
            $table->string('classroom');
            $table->string('requirements');
            $table->string('corequisites');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_courses');
    }
};
