<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Shift extends Model
{

// shiftsテーブルへまとめて保存・更新できるカラム
    protected $fillable = [
        'user_id',
        'shift_date',
        'start_time',
        'end_time',
        'wage',
        'break_duration',
        'salary',
    ];
    
 // シフトを登録したユーザーとの関連
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
