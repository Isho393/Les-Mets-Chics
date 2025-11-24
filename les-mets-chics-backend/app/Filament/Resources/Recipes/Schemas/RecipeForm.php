<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->default(auth()->id()),
                \Filament\Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (\Filament\Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')
                            ->required(),
                    ]),
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (\Filament\Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
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
                \Filament\Forms\Components\Grid::make(3)
                    ->schema([
                        TextInput::make('prep_time')
                            ->numeric()
                            ->suffix('mins'),
                        TextInput::make('cook_time')
                            ->numeric()
                            ->suffix('mins'),
                        TextInput::make('servings')
                            ->numeric(),
                    ]),
                \Filament\Forms\Components\Repeater::make('ingredients')
                    ->schema([
                        TextInput::make('item')->required(),
                        TextInput::make('quantity')->required(),
                    ])
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('steps')
                    ->schema([
                        Textarea::make('description')->required(),
                    ])
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->required()
                    ->default('draft'),
            ]);
    }
}
