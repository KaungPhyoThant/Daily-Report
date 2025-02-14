<?php

namespace App\Observers;

use App\Events\CommentCreate;
use App\Models\User;
use App\Models\Comment;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $dailyReport = $comment->daily_report_id;
        $smth = DailyReport::find($dailyReport);
        $user = $smth->user_id;
        $assignedTo = User::where('id' , $user)->first();

        $admins = User::where('role', 'admin')->get();

        $sender = Auth::user();

        if ($sender->role === 'admin') {
            Notification::make()
                ->title($sender->name)
                ->body('An admin has added a new comment to your daily report.')
                ->actions([
                    Action::make('read')
                        ->button()
                        ->url(route('filament.app.resources.daily-reports.view' , ['record' => $dailyReport]))
                        ->outlined()
                        ->markAsRead()
                ])
                ->sendToDatabase($assignedTo, isEventDispatched: true);
            $unreadCount = $assignedTo->unreadNotifications()->count();
            broadcast(new CommentCreate($assignedTo->id, 'An admin has added a new comment to your daily report.', $unreadCount));

        }

        if ($sender->role !== 'admin') {

            foreach ($admins as $user) {
                Notification::make()
                    ->title($sender->name)
                    ->body('The assigned user has added a new comment to a daily report.')
                    ->actions([
                        Action::make('read')
                            ->button()
                            ->url(route('filament.admin.resources.daily-reports.view', ['record' => $dailyReport]))
                            ->outlined()
                            ->markAsRead()
                    ])
                    ->sendToDatabase($user, isEventDispatched: true);
                $unreadCount = $user->unreadNotifications()->count();
                broadcast(new CommentCreate($user->id, 'The assigned user has added a new comment to a daily report.', $unreadCount));

            }
        }
    }
    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
