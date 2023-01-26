<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lrn')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->length(12),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->string(),
                Forms\Components\TextInput::make('middle_name')
                    ->required()
                    ->string(),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->string(),
                Forms\Components\TextInput::make('suffix')
                    ->nullable()
                    ->string(),
                Forms\Components\DatePicker::make('birthday')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lrn')->sortable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable()->label('First Name'),
                Tables\Columns\TextColumn::make('middle_name')->sortable(),
                Tables\Columns\TextColumn::make('last_name')->sortable(),
                Tables\Columns\TextColumn::make('suffix')->sortable(),
                Tables\Columns\TextColumn::make('birthday')->date()->sortable(),
            ])
            ->defaultSort('first_name', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
