<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bounus extends Model
{ protected $table = 'bounus'; // テーブル名を指定
    // bounsテーブルのデータを保存・取得・更新・削除するモデル
    protected $fillable = [
        'user_id',
        'bounus_date',
        'bounus_amount',
        'bounus_memo',
    ];
     // 登録したユーザーとの関連
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
