<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class PostsChart extends ChartWidget
{
    protected ?string $heading = 'Articles par catégorie';

    protected function getData(): array
    {
        $categories = Category::withCount('posts')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Articles',
                    'data' => $categories->pluck('posts_count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#06b6d4',
                        '#84cc16',
                        '#ec4899',
                    ],
                ],
            ],

            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}