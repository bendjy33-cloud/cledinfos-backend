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

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {

                        if (!$get('slug')) {
                            $set(
                                'slug',
                                Str::slug($state)
                            );
                        }

                    }),


                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),



                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),


                FileUpload::make('image')
                    ->label('Image principale')
                    ->image()
                    ->disk('public')
                    ->directory('posts')
                    ->visibility('public')
                    ->imageEditor()
                    ->required(),



                RichEditor::make('content')
                    ->label('Contenu')
                    ->required()
                    ->columnSpanFull(),



                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(255),



                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(4)
                    ->maxLength(160)
                    ->columnSpanFull(),



                TextInput::make('keywords')
                    ->label('Keywords')
                    ->helperText(
                        'Séparer par des virgules'
                    ),



                Toggle::make('featured')
                    ->label('Article à la Une')
                    ->default(false),



                Toggle::make('is_published')
                    ->label('Publié')
                    ->default(true),



                DateTimePicker::make('published_at')
                    ->label('Date publication')
                    ->default(now())
                    ->disabled(fn ($get) => !$get('is_published')),


            ]);
    }
}