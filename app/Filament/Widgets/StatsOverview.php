<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Contact;


class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [

             Stat::make('Articles', Post::count())
                ->description('Articles publiés')
                ->descriptionIcon('heroicon-m-newspaper')
                ->url('/admin/posts'),

            Stat::make('Catégories', Category::count())
                ->url('/admin/categories'),

            Stat::make('Newsletter', Newsletter::count())
                ->url('/admin/newsletters'),

            Stat::make('Messages', Contact::count())
                ->url('/admin/contacts'),

            Stat::make('Articles', Post::count())
                ->description('Articles publiés')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),

            Stat::make('Catégories', Category::count())
                ->description('Catégories actives')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('success'),

            Stat::make('Newsletter', Newsletter::count())
                ->description('Abonnés')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),

            Stat::make(
                'Vues',
                number_format(Post::sum('views'))
            )
                ->description('Total des vues')
                ->descriptionIcon('heroicon-m-eye')
                ->color('danger'),

            Stat::make('Messages', Contact::count())
                ->description('Messages reçus')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),
                    ];
    }
}