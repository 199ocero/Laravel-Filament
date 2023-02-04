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
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lrn')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->length(12)
                    ->placeholder('e.g. 784172592979'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('e.g. student@gmail.com'),
                Forms\Components\TextInput::make('first_name')
                    ->label('First Name')
                    ->required()
                    ->string()
                    ->placeholder('e.g. Jhon'),
                Forms\Components\TextInput::make('middle_name')
                    ->label('Middle Name')
                    ->required()
                    ->string()
                    ->placeholder('Pales'),
                Forms\Components\TextInput::make('last_name')
                    ->label('Last Name')
                    ->required()
                    ->string()
                    ->placeholder('e.g. Doe'),
                Forms\Components\TextInput::make('suffix')
                    ->nullable()
                    ->string()
                    ->placeholder('e.g. Jr.'),
                Forms\Components\DatePicker::make('birthday')
                    ->required()
                    ->placeholder('e.g. September 10, 1992'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lrn')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('middle_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('suffix')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('birthday')->date()->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStudents::route('/'),
        ];
    }
}
