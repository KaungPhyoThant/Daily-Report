<?php

namespace App\Filament\App\Widgets;

use App\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class TaskOrder extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query( Task::where('assigned_to' , Auth::id()) )
            ->defaultSort('status' , 'pending')
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('assignedBy.name'),
                TextColumn::make('status')
            ]);
    }
}
