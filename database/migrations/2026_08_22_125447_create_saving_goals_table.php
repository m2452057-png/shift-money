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
        Schema::create('saving_goals', function (Blueprint $table) {
            $table->id();
            // user_idを外部キーとして設定し、usersテーブルのidと紐づける
            $table->foreignId('user_id')
            // 一人一つ２件以上は登録できないようにする
            ->unique()
            ->constrained()
            // ユーザーが削除された時に、紐づくsaving_goalsのレコードも削除する
            ->cascadeOnDelete();

            $table->integer('money_savings')
            ->default(0);
            $table->integer('money_goal')
            ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_goals');
    }
};
