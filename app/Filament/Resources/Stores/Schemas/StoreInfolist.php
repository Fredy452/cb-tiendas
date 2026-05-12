<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Models\Store;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StoreInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('website')
                    ->placeholder('-'),
                TextEntry::make('logo_path')
                    ->placeholder('-'),
                TextEntry::make('img_path')
                    ->placeholder('-'),
                TextEntry::make('status'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('latitude')
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->placeholder('-'),
                TextEntry::make('approval_status'),
                TextEntry::make('approval_date')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('approval_user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Store $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
