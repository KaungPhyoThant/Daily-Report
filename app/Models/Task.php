<?php

namespace App\Models;

use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    protected $fillable = [
        'assigned_by' ,
        'assigned_to' ,
        'title' ,
        'description' ,
        'due_date' ,
        'status' ,
        'progress' ,
    ];

    public function assignedBy() : BelongsTo
    {
        return $this->belongsTo(User::class , 'assigned_by');
    }
    public function assignedTo() : BelongsTo
    {
        return $this->belongsTo(User::class , 'assigned_to');
    }

    // public function assignedTo(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class , );
    // }
}
