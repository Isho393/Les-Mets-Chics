<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RecipeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('category_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                ImageEntry::make('main_image_url'),
                TextEntry::make('video_url')
                    ->placeholder('-'),
                TextEntry::make('prep_time')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cook_time')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('servings')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
