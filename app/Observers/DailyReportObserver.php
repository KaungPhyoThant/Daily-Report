<?php

namespace App\Observers;

use App\Models\User;
use App\Models\DailyReport;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class DailyReportObserver
{
    /**
     * Handle the DailyReport "created" event.
     */
    public function created(DailyReport $dailyReport): void
    {
        $recepient = User::where('role','admin')->get();
        $sender = Auth::user()->name;
        Notification::make()
            ->title($sender)
            ->body('Daily Report')
            ->actions([
            Action::make('View Report')
                ->button()
                ->markAsRead()
                ->url(route('filament.app.resources.daily-reports.index', $dailyReport->id), shouldOpenInNewTab: false)
            ])
        ->sendToDatabase($recepient, isEventDispatched: true);
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
