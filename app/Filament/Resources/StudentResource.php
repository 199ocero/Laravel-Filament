<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Student;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

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
                Forms\Components\TextInput::make('student_no')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->regex('/^\d{2}-\d{4}$/')
                    ->placeholder('e.g. ##-####'),
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
                Tables\Columns\TextColumn::make('student_no')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('full_name')->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('birthday')->date()->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Delete')
                    ->action(function (Model $record) {
                        $user = User::find($record->user_id);
                        $user->removeRole('student');
                        $record->delete();
                        $user->delete();
                    })
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->requiresConfirmation()

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
