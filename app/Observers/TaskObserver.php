<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use App\Events\TaskNotification;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Notifications\TaskCreatedNotification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $recipient = User::find($task->assigned_to);
        $sender = User::find($task->assigned_by)->name;
        Notification::make()
            ->title($sender)
            ->body('A new task has been assigned to you.')
            ->actions([
                Action::make('read')
                    ->button()
                    ->url(route('filament.app.resources.tasks.index'))
                    ->outlined()
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipient, isEventDispatched: true);
        broadcast(new TaskNotification($task));
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
