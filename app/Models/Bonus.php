<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bonus extends Model
{ protected $table = 'bonuses'; // テーブル名を指定
    // bonusesテーブルのデータを保存・取得・更新・削除するモデル
    protected $fillable = [
        'user_id',
        'bonus_date',
        'bonus_amount',
        'bonus_memo',
    ];
     // 登録したユーザーとの関連
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
