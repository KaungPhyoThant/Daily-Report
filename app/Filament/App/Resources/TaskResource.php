<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('assigned_to', auth()->id());
    }

    protected static ?string $model = Task::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('assigned_by')
                //     ->numeric(),
                // Forms\Components\TextInput::make('assigned_to')
                //     ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->readOnly()
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->disabled()
                    ->readOnly()
                    ->columnSpanFull(),
                // Forms\Components\DatePicker::make('due_date')
                //     ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'pending',
                        'in_progress' => 'in_progress',
                        'completed' => 'completed'
                    ])
                    ->selectablePlaceholder(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('assignedBy.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'pending',
                        'in_progress' => 'in_progress',
                        'completed' => 'completed'
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
