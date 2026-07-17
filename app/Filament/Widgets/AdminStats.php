<?php

namespace App\Filament\Widgets;

use App\Models\Author;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\Post;
use App\Models\Tag;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Articles', Post::count())
                ->description('Total des articles')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Vues', number_format(Post::sum('views')))
                ->description('Vues totales')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Catégories', Category::count())
                ->description('Toutes les catégories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('warning'),

            Stat::make('Tags', Tag::count())
                ->description('Tous les tags')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),

            Stat::make('Auteurs', Author::count())
                ->description('Auteurs enregistrés')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('Commentaires', Comment::count())
                ->description('Commentaires publiés')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),

            Stat::make('Newsletters', Newsletter::count())
                ->description('Abonnés')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('success'),

            Stat::make('Contacts', Contact::count())
                ->description('Messages reçus')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('danger'),

        ];
    }
}