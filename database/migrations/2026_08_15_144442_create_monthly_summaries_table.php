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
        Schema::create('monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();
            
            $table->integer('year');
            $table->integer('month');

            $table->unsignedInteger('shift_total')->default(0);
            $table->unsignedInteger('expense_total')->default(0);
            $table->unsignedInteger('bonus_total')->default(0);
            
            $table->integer('savings_total')->default(0);
            $table->timestamps();
             // 同じユーザーの同じ年月は1件だけ
            $table->unique(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_summaries');
    }
};
