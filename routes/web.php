<?php


use App\Models\User;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Filament\Notifications\DatabaseNotification;
use Filament\Notifications\Events\DatabaseNotificationsSent;

Route::get('/', function () {
    return view('welcome');
});


