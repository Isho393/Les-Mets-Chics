<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Recipes', \App\Models\Recipe::count())
                ->description('Total recipes created')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),
            Stat::make('Pending Users', \App\Models\User::where('status', 'pending')->count())
                ->description('Users waiting for approval')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Comments to Approve', \App\Models\Comment::where('is_approved', false)->count())
                ->description('Comments waiting for moderation')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),
        ];
    }
}
