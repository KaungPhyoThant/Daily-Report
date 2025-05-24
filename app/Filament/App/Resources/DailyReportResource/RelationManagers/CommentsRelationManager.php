<?php

namespace App\Filament\App\Resources\DailyReportResource\RelationManagers;

use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('daily_report_id')
                    ->default(fn($livewire) => $livewire->ownerRecord->id),
                Hidden::make('user_id')
                    ->default(fn() => Auth::id()),
                Forms\Components\TextInput::make('content')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        $user = Auth::user();
        return $table
            ->columns([
                Panel::make([
                    Split::make([
                        Stack::make([
                            TextColumn::make('user.name')->label('User')
                                ->weight('bold')
                                ->color('primary'),

                            TextColumn::make('content')->label('Comment'),

                            TextColumn::make('created_at')->label('Date')
                                ->dateTime()
                                ->color('gray'),
                        ]),
                    ]),
                ]),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Add a New Comment')
                    ->modalSubmitActionLabel('Post Comment'),
            ])
            ->actions([
            Action::make('Delete')
                ->icon('heroicon-o-trash')
                ->label('Delete')
                ->color('danger')
                ->successNotificationTitle('Comment Deleted Successfully')
                ->modalHeading('Confirm Deletion')
                ->modalSubmitActionLabel('Delete Comment')
                ->visible()
                ->action(function ($record) {
                    $record->delete();
                })
            ]);
    }
}
