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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');
            
            $table->date('shift_date');
            $table->unsignedInteger('start_time');
            $table->unsignedInteger('start_minute');
            $table->unsignedInteger('end_time');
            $table->unsignedInteger('end_minute');
            $table->unsignedInteger('wage'); // 時給（円）
            $table->unsignedInteger('break_time')->default(0); // break_timeを指定しない → 0を保存
            $table->unsignedInteger('break_minute')->default(0); // break_minuteを指定しない → 0を保存
            $table->unsignedInteger('salary');// 給与（円）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
