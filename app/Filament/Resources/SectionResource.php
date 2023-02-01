<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Campus;
use App\Models\Section;
use App\Models\District;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SectionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SectionResource\RelationManagers;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-template';
    protected static ?string $navigationGroup = 'Data Management';
    protected static ?int $navigationSort = 9;

    public $sectionId;

    public static function form(Form $form, $sectionId = 0): Form
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
                    ->options(function ($get) {
                        $campuses = Campus::where('district_id', $get('district_id'))->get();

                        if ($campuses) {
                            return $campuses->pluck('name', 'id');
                        }
                    }),
                Forms\Components\TextInput::make('id')
                    ->default($sectionId)
                    ->dehydrated(false)
                    ->hidden(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Section')
                    ->placeholder('e.g. Section A')
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $record = Section::where('name', $value)
                                    ->where('campus_id', $get('campus_id'))
                                    ->where('id', '!=', $get('id'))->first();
                                if ($record) {
                                    $fail("The section $value already exists in selected district and campus.");
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
                    ->label('Section')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data) use (&$sectionId): array {

                        $campus = Campus::find($data['campus_id']);
                        $data['district_id'] = $campus->district_id;

                        $sectionId = $data['id'];
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
            'index' => Pages\ManageSections::route('/'),
        ];
    }
}
