<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'daily_report_id' ,
        'user_id' ,
        'content'
    ];

    public function report() : BelongsTo
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
