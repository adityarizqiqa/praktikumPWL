<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Grid;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'md' => 3,
                ])
                    ->schema([
                        // left (2/3)
                        Section::make('Post Details')
                            ->description('Fill in the details of the post')
                            ->icon('heroicon-o-document-text')
                            ->columnSpan([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->columns(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->minLength(5),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->searchable(),
                                ColorPicker::make('color'),
                                RichEditor::make('body')
                                    ->label('Content')
                                    ->columnSpanFull(),
                            ]),

                        // right (1/3)
                        Group::make([
                            Section::make('Image Upload')
                                ->icon('heroicon-o-photo')
                                ->compact()
                                ->schema([
                                    FileUpload::make('image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('posts'),
                                ]),

                            Section::make('Meta Information')
                                ->icon('heroicon-o-tag')
                                ->compact()
                                ->columns(2)
                                ->schema([
                                    TagsInput::make('tags'),
                                    Toggle::make('published'),
                                ]),

                            Section::make('Published at')
                                ->icon('heroicon-o-calendar-days')
                                ->compact()
                                ->schema([
                                    DatePicker::make('published_at'),
                                ]),
                        ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}

