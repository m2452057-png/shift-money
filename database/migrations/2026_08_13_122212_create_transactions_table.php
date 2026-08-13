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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');
            //シフト・浪費・ボーナスが発生した日
            $table->date('transaction_date');
            //  金額
            $table->unsignedInteger('transaction_amount');
            // 0:シフト, 1:浪費, 2:ボーナス
            $table->unsignedInteger('transaction_type');

            // ユーザー別・月別の集計をしやすくする
            $table->index(['user_id','transaction_date']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
