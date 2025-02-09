<?php

namespace App\Filament\App\Resources\DailyReportResource\Pages;

use App\Filament\App\Resources\DailyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyReport extends EditRecord
{
    protected static string $resource = DailyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
