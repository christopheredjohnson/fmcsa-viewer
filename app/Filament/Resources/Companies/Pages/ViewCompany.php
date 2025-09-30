<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send_email')
                ->label('Send Quick Email')
                ->icon('heroicon-o-envelope')
                ->form([
                    TextInput::make('subject')->required(),
                    Textarea::make('message')->required(),
                ])
                ->action(function (array $data, $record) {
                    Notification::make()
                        ->title('Email sent!')
                        ->body("Subject: {$data['subject']}")
                        ->success()
                        ->send();
                })
                ->visible(fn ($record) => filled($record->email_address)),
        ];
    }
}
