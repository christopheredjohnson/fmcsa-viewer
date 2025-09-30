<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Info')
                    ->schema([
                        TextEntry::make('dot_number')->label('DOT Number'),
                        TextEntry::make('legal_name')->label('Legal Name'),
                        TextEntry::make('dba_name')->label('DBA Name'),
                        TextEntry::make('business_org_desc')->label('Organization Type'),
                        TextEntry::make('status_code')->label('Status'),
                        TextEntry::make('add_date')->label('Added'),
                        TextEntry::make('safety_rating')->label('Safety Rating'),
                        TextEntry::make('safety_rating_date')->label('Safety Rating Date'),
                    ])->columns(2),
                Section::make('Contact')
                    ->schema([
                        TextEntry::make('phone'),
                        TextEntry::make('fax'),
                        TextEntry::make('cell_phone'),
                        TextEntry::make('email_address'),
                    ])->columns(2),
                Section::make('Addresses')
                    ->schema([
                        RepeatableEntry::make('addresses')
                            ->schema([
                                TextEntry::make('type')->label('Type'),
                                TextEntry::make('street'),
                                TextEntry::make('city'),
                                TextEntry::make('state'),
                                TextEntry::make('zip'),
                                TextEntry::make('country'),
                            ])
                            
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
