<?php

namespace App\Filament\App\Resources\DailyReportResource\RelationManagers;

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


                            // ->html() // Enables HTML formatting if needed
                            // ->wrap()
                            // ->limit(200),
                    ]),
                ]),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([])
            ->actions([])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Add a New Comment')
                    ->modalSubmitActionLabel('Post Comment'),
            ]);
    }
}
            // ])
            // ->filters([
            //     //
            // ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            //     Tables\Actions\DeleteAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
//     }
// }
