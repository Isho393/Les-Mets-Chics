<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RecipesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Recipes Created';

    protected function getData(): array
    {
        $data = \Flowframe\Trend\Trend::model(\App\Models\Recipe::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Recipes',
                    'data' => $data->map(fn (\Flowframe\Trend\TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (\Flowframe\Trend\TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
