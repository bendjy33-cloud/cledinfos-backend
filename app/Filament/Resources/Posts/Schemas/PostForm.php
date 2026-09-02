<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ========================================
            // FRENCH
            // ========================================

            TextInput::make('title_fr')
                ->label('Titre (Français)')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    if (! $get('slug') && filled($state)) {
                        $set('slug', Str::slug($state));
                    }
                }),

            Textarea::make('subtitle_fr')
                ->label('Sous-titre (Français)')
                ->rows(3)
                ->columnSpanFull(),

            RichEditor::make('content_fr')
                ->label('Contenu (Français)')
                ->required()
                ->columnSpanFull(),

            Textarea::make('meta_description_fr')
                ->label('Meta Description (Français)')
                ->rows(4)
                ->maxLength(160)
                ->columnSpanFull(),

            TextInput::make('keywords_fr')
                ->label('Keywords (Français)')
                ->helperText('Séparer par des virgules'),

            // ========================================
            // ENGLISH
            // ========================================

            TextInput::make('title_en')
                ->label('Title (English)'),

            Textarea::make('subtitle_en')
                ->label('Subtitle (English)')
                ->rows(3)
                ->columnSpanFull(),

            RichEditor::make('content_en')
                ->label('Content (English)')
                ->columnSpanFull(),

            Textarea::make('meta_description_en')
                ->label('Meta Description (English)')
                ->rows(4)
                ->maxLength(160)
                ->columnSpanFull(),

            TextInput::make('keywords_en')
                ->label('Keywords (English)')
                ->helperText('Separate with commas'),

            // ========================================
            // KREYÒL
            // ========================================

            TextInput::make('title_ht')
                ->label('Tit (Kreyòl)'),

            Textarea::make('subtitle_ht')
                ->label('Soutit (Kreyòl)')
                ->rows(3)
                ->columnSpanFull(),

            RichEditor::make('content_ht')
                ->label('Kontni (Kreyòl)')
                ->columnSpanFull(),

            Textarea::make('meta_description_ht')
                ->label('Meta Description (Kreyòl)')
                ->rows(4)
                ->maxLength(160)
                ->columnSpanFull(),

            TextInput::make('keywords_ht')
                ->label('Keywords (Kreyòl)')
                ->helperText('Separe ak vigil'),

            // ========================================
            // SPANISH
            // ========================================

            TextInput::make('title_es')
                ->label('Título (Español)'),

            Textarea::make('subtitle_es')
                ->label('Subtítulo (Español)')
                ->rows(3)
                ->columnSpanFull(),

            RichEditor::make('content_es')
                ->label('Contenido (Español)')
                ->columnSpanFull(),

            Textarea::make('meta_description_es')
                ->label('Meta Description (Español)')
                ->rows(4)
                ->maxLength(160)
                ->columnSpanFull(),

            TextInput::make('keywords_es')
                ->label('Keywords (Español)')
                ->helperText('Separar por comas'),

            // ========================================
            // GENERAL
            // ========================================

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            // ========================================
            // CATEGORY
            // ========================================

            Select::make('category_id')
                ->label('Catégorie')
                ->relationship('category', 'name_fr')
                ->getOptionLabelFromRecordUsing(
                    fn ($record): string =>
                        $record->name_fr
                        ?? $record->name_en
                        ?? $record->name_ht
                        ?? $record->name_es
                        ?? 'Sans catégorie'
                )
                ->searchable()
                ->preload()
                ->required(),

            // ========================================
            // AUTHOR
            // ========================================

            Select::make('author_id')
                ->label('Auteur')
                ->relationship('author', 'name')
                ->getOptionLabelFromRecordUsing(
                    fn ($record): string =>
                        $record->name ?? 'Auteur sans nom'
                )
                ->searchable()
                ->preload()
                ->required(),

            // ========================================
            // TAGS
            // ========================================

            Select::make('tags')
                ->label('Tags')
                ->relationship('tags', 'name')
                ->getOptionLabelFromRecordUsing(
                    fn ($record): string =>
                        $record->name ?? 'Tag sans nom'
                )
                ->multiple()
                ->preload()
                ->searchable(),

            // ========================================
            // IMAGE
            // ========================================

            FileUpload::make('image')
                ->label('Image principale')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('posts')
                ->visibility('public')
                ->required(),


            // ========================================
            // GALERIE PHOTOS
            // ========================================

            Repeater::make('images')
                ->label('Photos supplémentaires')
                ->relationship('images')
                ->schema([
                    FileUpload::make('image')
                        ->label('Photo')
                        ->image()
                        ->imageEditor()
                        ->disk('public')
                        ->directory('posts/gallery')
                        ->visibility('public')
                        ->required(),
                ])
                ->reorderable()
                ->collapsible()
                ->itemLabel(
                    fn (array $state): ?string =>
                        filled($state['image'] ?? null)
                            ? 'Photo'
                            : 'Nouvelle photo'
                )
                ->columnSpanFull(),

            // ========================================
            // OPTIONS
            // ========================================

            Toggle::make('featured')
                ->label('Article à la Une')
                ->default(false),

            Toggle::make('is_published')
                ->label('Publié')
                ->default(true),

            DateTimePicker::make('published_at')
                ->label('Date publication')
                ->default(now())
                ->disabled(fn ($get) => ! $get('is_published')),
        ]);
    }
}