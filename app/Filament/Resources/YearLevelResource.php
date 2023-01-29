<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Campus;
use App\Models\District;
use App\Models\YearLevel;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\YearLevelResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\YearLevelResource\RelationManagers;

class YearLevelResource extends Resource
{
    protected static ?string $model = YearLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Data Management';
    protected static ?int $navigationSort = 5;

    public $yearLevelId;

    public static function form(Form $form, $yearLevelId = 0): Form
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
                    ->default($yearLevelId)
                    ->dehydrated(false)
                    ->hidden(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $record = YearLevel::where('name', $value)
                                    ->where('campus_id', $get('campus_id'))
                                    ->where('id', '!=', $get('id'))->first();
                                if ($record) {
                                    $fail("The year level $value already exists in selected district and campus.");
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
                    ->label('Year Level')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data) use (&$yearLevelId): array {
                        $yearLevelId = $data['id'];
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
            'index' => Pages\ManageYearLevels::route('/'),
        ];
    }
}
