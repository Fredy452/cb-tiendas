<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('address'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('website')
                    ->url(),
                TextInput::make('logo_path'),
                TextInput::make('img_path'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('latitude'),
                TextInput::make('longitude'),
                TextInput::make('approval_status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('approval_date'),
                TextInput::make('approval_user_id')
                    ->numeric(),
            ]);
    }
}
