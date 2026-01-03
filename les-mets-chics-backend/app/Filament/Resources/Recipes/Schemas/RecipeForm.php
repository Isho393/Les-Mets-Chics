<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(fn () => auth()->id()),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->required(),
                    ]),
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('main_image_url')
                    ->image()
                    ->directory('recipes')
                    ->required(),
                TextInput::make('video_url')
                    ->url()
                    ->suffixIcon('heroicon-m-video-camera'),
                TextInput::make('prep_time')
                    ->numeric()
                    ->suffix('mins')
                    ->columnSpan(1),
                TextInput::make('cook_time')
                    ->numeric()
                    ->suffix('mins')
                    ->columnSpan(1),
                TextInput::make('servings')
                    ->numeric()
                    ->columnSpan(1),
                Repeater::make('ingredients')
                    ->schema([
                        TextInput::make('item')->required(),
                        TextInput::make('quantity')->required(),
                    ])
                    ->columnSpanFull(),
                Repeater::make('steps')
                    ->schema([
                        Textarea::make('description')->required(),
                    ])
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->required()
                    ->default('draft'),
            ]);
    }
}
