<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    protected $table = 'monthly_summaries'; // テーブル名を指定
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'shift_total',
        'expense_total',
        'bonus_total',
        'savings_total',
    ];


}
