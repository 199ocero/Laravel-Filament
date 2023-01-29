<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Campus;
use App\Models\Course;
use App\Models\District;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Department;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $navigationGroup = 'Data Management';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('district_id')
                    ->required()
                    ->label('District')
                    ->dehydrated(false)
                    ->options(District::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('campus_id', null)),

                Forms\Components\Select::make('campus_id')
                    ->required()
                    ->label('Campus')
                    ->dehydrated(false)
                    ->options(function ($get) {
                        $campuses = Campus::where('district_id', $get('district_id'))->get();

                        if ($campuses) {
                            return $campuses->pluck('name', 'id');
                        }
                    })
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('department_id', null)),

                Forms\Components\Select::make('department_id')
                    ->required()
                    ->label('Department')
                    ->options(function ($get) {
                        $department = Department::where('campus_id', $get('campus_id'))->get();

                        if ($department) {
                            return $department->pluck('name', 'id');
                        }
                    }),

                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->string()
                    ->placeholder('e.g. BSIT'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Course')
                    ->unique(ignoreRecord: true)
                    ->string()
                    ->placeholder('e.g. Bachelor of Science in Information Technology')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.campus.district.name')
                    ->label('District')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.campus.name')
                    ->label('Campus')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Course')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data) use (&$departmentId): array {

                        $department = Department::find($data['department_id']);
                        $campus = Campus::find($department->campus_id);

                        $data['district_id'] = $campus->district_id;

                        $data['campus_id'] = $department->campus_id;

                        $data['department_id'] = $department->id;

                        $departmentId = $data['id'];

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCourses::route('/'),
        ];
    }
}
