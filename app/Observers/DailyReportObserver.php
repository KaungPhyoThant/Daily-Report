<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DailyReport;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Actions\ActionGroup;

class DailyReportObserver
{
    /**
     * Handle the DailyReport "created" event.
     */
    public function created(DailyReport $dailyReport): void
    {
        $recepient = User::where('role', 'admin')->get();
        $sender = Auth::user()->name;
        Notification::make()
            ->title($sender)
            ->body('Daily Report')
            ->actions([
                Action::make('read')
                    ->button()
                    ->url(route('filament.admin.resources.daily-reports.index'))
                    ->outlined()
                    ->markAsRead()
            ])
            ->sendToDatabase($recepient, isEventDispatched: true);

        foreach ($recepient as $user) {
            $count = $user->unreadNotifications()->count();
            broadcast(new NewNotification($user->id, $count));
        }
    }

    /**
     * Handle the DailyReport "updated" event.
     */
    public function updated(DailyReport $dailyReport): void
    {
        //
    }

    /**
     * Handle the DailyReport "deleted" event.
     */
    public function deleted(DailyReport $dailyReport): void
    {
        //
    }

    /**
     * Handle the DailyReport "restored" event.
     */
    public function restored(DailyReport $dailyReport): void
    {
        //
    }

    /**
     * Handle the DailyReport "force deleted" event.
     */
    public function forceDeleted(DailyReport $dailyReport): void
    {
        //
    }
}
