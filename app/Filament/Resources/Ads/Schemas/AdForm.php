<?php

namespace App\Filament\Resources\Ads\Schemas;

use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =====================================================
                // TITRES MULTILINGUES
                // =====================================================

                TextInput::make('title_fr')
                    ->label('Titre — Français')
                    ->required()
                    ->maxLength(255),

                TextInput::make('title_ht')
                    ->label('Tit — Kreyòl')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('title_en')
                    ->label('Title — English')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('title_es')
                    ->label('Título — Español')
                    ->nullable()
                    ->maxLength(255),

                // =====================================================
                // POSITION
                // =====================================================

                Select::make('position')
                    ->label('Position')
                    ->options([
                        'header' => 'Header',
                        'sidebar' => 'Sidebar',
                        'article' => 'Article',
                        'footer' => 'Footer',
                    ])
                    ->required(),

                // =====================================================
                // ARTICLE ASSOCIÉ
                // =====================================================

                Select::make('post_id')
                    ->label('Article associé')
                    ->options(function () {
                        return Post::query()
                            ->orderByDesc('published_at')
                            ->get()
                            ->mapWithKeys(function (Post $post) {
                                $title = $post->title_fr
                                    ?? $post->title_ht
                                    ?? $post->title_en
                                    ?? $post->title_es
                                    ?? 'Article #' . $post->id;

                                return [
                                    $post->id => $title,
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText(
                        'Sélectionnez l’article auquel cette publicité sera associée.'
                    ),

                // =====================================================
                // IMAGE
                // =====================================================

                FileUpload::make('image')
                    ->label('Image publicitaire')
                    ->image()
                    ->directory('ads')
                    ->disk('public')
                    ->nullable()
                    ->maxSize(10240),

                // =====================================================
                // VIDEO
                // =====================================================

                FileUpload::make('video')
                    ->label('Vidéo publicitaire')
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/webm',
                        'video/ogg',
                        'video/quicktime',
                    ])
                    ->directory('ads/videos')
                    ->disk('public')
                    ->nullable()
                    ->rules([
                        'file',
                        'max:102400',
                    ])
                    ->maxSize(102400),

                // =====================================================
                // LIEN
                // =====================================================

                TextInput::make('url')
                    ->label('Lien')
                    ->url()
                    ->nullable(),

                // =====================================================
                // STATUT
                // =====================================================

                Toggle::make('active')
                    ->label('Actif')
                    ->default(true),

                // =====================================================
                // DATES
                // =====================================================

                DateTimePicker::make('starts_at')
                    ->label('Début')
                    ->nullable(),

                DateTimePicker::make('ends_at')
                    ->label('Fin')
                    ->nullable(),

            ]);
    }
}