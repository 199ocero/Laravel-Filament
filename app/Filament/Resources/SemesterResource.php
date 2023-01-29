<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Campus;
use App\Models\District;
use App\Models\Semester;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SemesterResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SemesterResource\RelationManagers;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $navigationIcon = 'heroicon-o-lightning-bolt';
    protected static ?string $navigationGroup = 'Data Management';
    protected static ?int $navigationSort = 6;

    public $semesterId;

    public static function form(Form $form, $semesterId = 0): Form
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
                    }),
                Forms\Components\TextInput::make('id')
                    ->default($semesterId)
                    ->dehydrated(false)
                    ->hidden(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('e.g. 1st Semester')
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $record = Semester::where('name', $value)
                                    ->where('campus_id', $get('campus_id'))
                                    ->where('id', '!=', $get('id'))->first();
                                if ($record) {
                                    $fail("The semester $value already exists in selected district and campus.");
                                }
                            };
                        },
                    ])
                    ->string(),
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
                    ->label('Semester')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data) use (&$semesterId): array {

                        $campus = Campus::find($data['campus_id']);
                        $data['district_id'] = $campus->district_id;

                        $semesterId = $data['id'];
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
            'index' => Pages\ManageSemesters::route('/'),
        ];
    }
}
