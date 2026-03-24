<?php

namespace App\Filament\Resources\Pacients;

use App\Filament\Resources\Pacients\Pages\ManagePacients;
use App\Models\Pacient;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PacientResource extends Resource
{
    protected static ?string $model = Pacient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('lastname')
                    ->required(),
                TextInput::make('phone_number')
                    ->tel()
                    ->required(),
                TextInput::make('dui')
                    ->default(null),
                TextInput::make('gender')
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del Paciente')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('lastname'),
                        TextEntry::make('phone_number'),
                        TextEntry::make('dui')
                            ->placeholder('-'),
                        TextEntry::make('gender')
                            ->badge()
                            ->color(fn (string $state) => match($state) {
                                'male'=> 'info',
                                'female'=> 'pink',
                                default => 'gray',
                            }),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
                Section::make('Expediente Clínico')
                ->icon('heroicon-o-clipboard-document-list')
                ->relationship('MedicalHistory')
                ->columns(2)
                ->schema([
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
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('dui')
                    ->searchable(),
                TextColumn::make('gender')
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
            'index' => ManagePacients::route('/'),
        ];
    }
}
