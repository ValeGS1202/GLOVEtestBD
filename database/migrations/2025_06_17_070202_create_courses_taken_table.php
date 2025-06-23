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
        Schema::create('courses_taken', function (Blueprint $table) {
            $table->id();
          //  $table->foreignId('course_id')->constrained('tb_courses')->onDelete('cascade')->nullable(); //todavia no existe tabla de cursos
            $table->string('status');
            $table->decimal('grade', 3, 2);
            $table->string('semester_coursed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_taken');
    }
};
