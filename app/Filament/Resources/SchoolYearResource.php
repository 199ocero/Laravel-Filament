<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Campus;
use App\Models\District;
use App\Models\SchoolYear;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Builder;
use App\Rules\SchoolYear as RulesSchoolYear;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SchoolYearResource\Pages;
use App\Filament\Resources\SchoolYearResource\RelationManagers;

class SchoolYearResource extends Resource
{
    protected static ?string $model = SchoolYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Data Management';
    protected static ?int $navigationSort = 4;

    public $schoolYearId;

    public static function form(Form $form, $schoolYearId = 0): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('district_id')
                    ->required()
                    ->dehydrated(false)
                    ->options(District::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('campus_id', null)),

                Forms\Components\Select::make('campus_id')
                    ->required()
                    ->options(function ($get) {
                        $campuses = Campus::where('district_id', $get('district_id'))->get();

                        if ($campuses) {
                            return $campuses->pluck('name', 'id');
                        }
                    })
                    ->label('Campus')
                    ->helperText('Choose a district first before you can select campus options.'),

                Forms\Components\Select::make('status_id')
                    ->required()
                    ->relationship('status', 'name'),

                Forms\Components\TextInput::make('id')
                    ->default($schoolYearId)
                    ->dehydrated(false)
                    ->hidden(),

                Forms\Components\TextInput::make('name')
                    ->label('School Year')
                    ->validationAttribute('school year')
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $school_year = $value . '-' . ($value + 1);
                                $record = SchoolYear::where('name', $school_year)->where('id', '!=', $get('id'))->first();
                                if ($record) {
                                    $fail("The school year $school_year already exists in selected district and campus.");
                                }
                            };
                        },
                    ])
                    ->required()
                    ->numeric()
                    ->length(4)
                    ->helperText('Note: If you put 2023, this will save as 2023-2024 in the database.')
                    ->placeholder('e.g. 2023'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('campus.district.name')
                    ->label('District')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('campus.name')
                    ->label('Campus')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('School Year')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ViewColumn::make('status.name')
                    ->view('filament.tables.columns.status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data) use (&$schoolYearId): array {

                        $schoolYearId = $data['id'];

                        $campus = Campus::find($data['campus_id']);
                        $data['district_id'] = $campus->district_id;

                        $parts = explode("-", $data['name']);
                        $year = intval($parts[0]);
                        $data['name'] = $year;

                        return $data;
                    })
                    ->using(function (SchoolYear $record, array $data): SchoolYear {

                        $year = intval($data['name']);
                        $data['name'] = $data['name'] . "-" . ($year + 1);

                        $record->update($data);

                        return $record;
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
            'index' => Pages\ManageSchoolYears::route('/'),
        ];
    }
}
