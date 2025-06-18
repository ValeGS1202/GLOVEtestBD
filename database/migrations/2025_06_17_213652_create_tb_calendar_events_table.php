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
        Schema::create('tb_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('date');
            $table->time('time');
            $table->date('reminder_date');
            $table->time('reminder_time');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //el constrained es para decir que se relaciona con la tabla users
            //$table->foreignId('course_id')->constrained('tb_courses')->onDelete('cascade')->nullable();//todavia no existe tabla de cursos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_calendar_events');
    }
};
