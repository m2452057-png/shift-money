<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $table = 'expenses'; // テーブル名を指定
    // expensesテーブルのデータを保存・
    protected $fillable = [
        'user_id',
        'expense_date',
        'expense_amount',
        'expense_memo',
    ];  
     // 登録したユーザーとの関連
    public function user()
    {
        return $this->belongsTo(User::class);
    }            
    
}
