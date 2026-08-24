<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Shift extends Model
{
    protected $table = 'shifts'; // テーブル名を指定

// shiftsテーブルへまとめて保存・更新できるカラム
    protected $fillable = [
        'user_id',
        'shift_date',
        'start_time',
        'start_minute',
        'end_time',
        'end_minute',
        'wage',
        'break_time',
        'break_minute',
        'salary',
    ];
    
 // シフトを登録したユーザーとの関連
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
