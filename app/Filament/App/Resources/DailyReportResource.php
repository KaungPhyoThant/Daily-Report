<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DailyReport;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\HasManyRepeater;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\DailyReportResource\Pages;
use App\Filament\App\Resources\DailyReportResource\RelationManagers;
use App\Filament\App\Resources\DailyReportResource\RelationManagers\CommentsRelationManager;
use Filament\Forms\Components\Hidden;

class DailyReportResource extends Resource
{

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    protected static ?string $model = DailyReport::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),
                Forms\Components\Select::make('task_id')
                    ->relationship('task', 'title')
                    ->required(),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => Auth::user()->id),
                RichEditor::make('content')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'strike',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'link',
                        'h2',
                        'h3',
                        'blockquote',
                        'codeBlock'
                    ])
                    ->fileAttachmentsVisibility('public'),

            // Section::make('Comments')
            //     ->schema([
            //         Repeater::make('comments')
            //             ->relationship('comments')
            //             ->schema([
            //                 Hidden::make('user_id')
            //                     ->default(fn() => Auth::id()),

            //                 Textarea::make('content')
            //                     ->label('Comment'),
            //             ])
            //             ->columns(1),
            // ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->query(fn(Builder $query) => $query->when(auth()->check(), fn($q) => $q->where('user_id', auth()->id())))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('task.title')
                    ->searchable(),
                TextColumn::make('content')
                    ->html()
                    ->formatStateUsing(fn($state) => str_replace('src="http://localhost/storage/', 'src="/storage/', $state)),
                //     ->hidden(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyReports::route('/'),
            'create' => Pages\CreateDailyReport::route('/create'),
            'edit' => Pages\EditDailyReport::route('/{record}/edit'),
            'view' => Pages\ViewDailyReport::route('/{record}/view'),
        ];
    }
}
