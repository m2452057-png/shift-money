<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    // expensesテーブルのデータを保存・
    protected $fillable = [
        'user_id',
        'expense_date',
        'expense_amount',
        'expense_memo',
    ];              
    
}
