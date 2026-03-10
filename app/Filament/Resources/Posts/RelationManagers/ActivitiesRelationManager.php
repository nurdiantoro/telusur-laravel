<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('causer.name')
                    ->label('User'),

                TextColumn::make('changes')
                    ->label('Changes')
                    ->getStateUsing(function ($record) {

                        $old = $record->properties['old'] ?? [];
                        $new = $record->properties['attributes'] ?? [];

                        $output = [];

                        foreach ($new as $key => $value) {
                            $oldValue = $old[$key] ?? '-';
                            $output[] = "{$key}: {$oldValue} → {$value}";
                        }

                        return implode("\n", $output);
                    })
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Date Time')
                    ->dateTime(),
            ]);
    }
}
