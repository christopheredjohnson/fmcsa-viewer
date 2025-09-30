<?php

namespace App\Filament\Resources\Companies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dot_number')->sortable()->searchable(),
                TextColumn::make('legal_name')->sortable()->searchable(),
                TextColumn::make('dba_name')->searchable(),
                TextColumn::make('status_code')->label('Status')
            ])
            ->filters([
                SelectFilter::make('status_code')
                    ->options([
                        'A' => 'Active',
                        'I' => 'Inactive',
                    ])
            ])
            ->defaultSort('dot_number', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
