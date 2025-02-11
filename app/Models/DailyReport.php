<?php

namespace App\Models;

use App\Observers\DailyReportObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([DailyReportObserver::class])]
class DailyReport extends Model
{
    protected $fillable = [
        'user_id' ,
        'content' ,
        'date' ,
        'task_id' ,
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function task() : BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
