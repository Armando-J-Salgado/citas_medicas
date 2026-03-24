<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Resources\Appointments\Pages\ManageAppointments;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        $rules = (new StoreAppointmentRequest())->rules();
        return $schema
            ->components([
                DateTimePicker::make('start_at')
                    ->required(),
                DateTimePicker::make('end_at')
                    ->required()
                    ->rules($rules['end_at'] ?? []),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->rules($rules['user_id'] ?? []),
                Select::make('pacient_id')
                    ->relationship('pacient', 'name')
                    ->required()
                    ->rules($rules['pacient_id'] ?? []),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('start_at')
                    ->dateTime(),
                TextEntry::make('end_at')
                    ->dateTime(),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('pacient.name')
                    ->label('Pacient'),
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
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('pacient.name')
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
            'index' => ManageAppointments::route('/'),
        ];
    }
}
