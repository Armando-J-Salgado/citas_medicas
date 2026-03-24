<?php

namespace App\Filament\Resources\Schedules;

use App\Filament\Resources\Schedules\Pages\ManageSchedules;
use App\Models\Schedule;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected const DAYS = [
        0=> 'Domingo',
        1=> 'Lunes',
        2=> 'Martes',
        3=> 'Miércoles',
        4=> 'Jueves',
        5=> 'Viernes',
        6=> 'Sábado',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('day_of_week')
                    ->options(self::DAYS)
                    ->required(),
                TimePicker::make('start_at')
                    ->required(),
                TimePicker::make('end_at')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('day_of_week')
                    ->formatStateUsing(fn (int $state) => self::DAYS[$state] ?? $state),
                TextEntry::make('start_at')
                    ->time(),
                TextEntry::make('end_at')
                    ->time(),
                TextEntry::make('user.name')
                    ->label('User'),
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
                TextColumn::make('day_of_week')
                    ->formatStateUsing(fn (int $state) => self::DAYS[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('start_at')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->time()
                    ->sortable(),
                TextColumn::make('user.name')
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
            'index' => ManageSchedules::route('/'),
        ];
    }
}
