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
                                    ->minLength(5)
                                    ->validationMessages([
                                        'required' => 'Title wajib diisi.',
                                        'min' => 'Title minimal 5 karakter.',
                                    ]),
                                TextInput::make('slug')
                                    ->required()
                                    ->minLength(3)
                                    ->unique(ignoreRecord: true)
                                    ->validationMessages([
                                        'required' => 'Slug wajib diisi.',
                                        'min' => 'Slug minimal 3 karakter.',
                                        'unique' => 'Slug harus unik dan tidak boleh sama.',
                                    ]),
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Category wajib dipilih.',
                                    ])
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
                                ->columnSpanFull()
                                ->columns(1)
                                ->schema([
                                    FileUpload::make('image')
                                        ->image()
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'Image wajib diupload.',
                                        ])
                                        ->disk('public')
                                        ->directory('posts'),
                                ]),

                            Section::make('Meta Information')
                                ->icon('heroicon-o-tag')
                                ->compact()
                                ->columnSpanFull()
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

