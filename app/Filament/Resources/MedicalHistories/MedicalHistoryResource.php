<?php

namespace App\Filament\Resources\MedicalHistories;

use App\Filament\Resources\MedicalHistories\Pages\ManageMedicalHistories;
use App\Models\MedicalHistory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MedicalHistoryResource extends Resource
{
    protected static ?string $model = MedicalHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pacient_id')
                    ->relationship('pacient', 'name')
                    ->required(),
                TextInput::make('weight')
                    ->required()
                    ->numeric(),
                TextInput::make('height')
                    ->required()
                    ->numeric(),
                TextInput::make('chronic_diseases')
                    ->default(null),
                TextInput::make('allergies')
                    ->default(null),
                DatePicker::make('date_of_birth')
                    ->required(),
                TextInput::make('medications')
                    ->default(null),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('pacient.name')
                    ->label('Pacient'),
                TextEntry::make('weight')
                    ->numeric(),
                TextEntry::make('height')
                    ->numeric(),
                TextEntry::make('chronic_diseases')
                    ->placeholder('-'),
                TextEntry::make('allergies')
                    ->placeholder('-'),
                TextEntry::make('date_of_birth')
                    ->date(),
                TextEntry::make('medications')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('pacient.name')
                    ->searchable(),
                TextColumn::make('weight')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('height')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('chronic_diseases')
                    ->searchable(),
                TextColumn::make('allergies')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('medications')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMedicalHistories::route('/'),
        ];
    }
}
