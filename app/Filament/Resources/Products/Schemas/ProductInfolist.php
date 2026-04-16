<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Section::make('Product Info')
                ->description('')
                ->schema([
                TextEntry::make('name')
                    ->label('Product Name')
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('id')
                    ->label('Product ID'),
                TextEntry::make('sku')
                    ->label('Product SKU')
                    ->badge()
                    ->color(function ($state): string {
                        if (blank($state)) {
                            return 'gray';
                        }

                        $colors = ['primary', 'success', 'warning', 'info', 'danger'];
                        $hash = (int) sprintf('%u', crc32((string) $state));

                        return $colors[$hash % count($colors)];
                    }),
                TextEntry::make('description')
                    ->label('Product Description'),
                TextEntry::make('created_at')
                    ->label('Product Created Date')
                    ->dateTime('d M Y')
                    ->color('info'),
                ])
                ->columnSpanFull(),

                Section::make('Price and Stock')
                ->schema([
                TextEntry::make('price')
                    ->label('Product Price')
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-banknotes')
                    ->formatStateUsing(fn ($state) => $state === null ? '-' : 'Rp ' . number_format((float) $state, 0, ',', '.')),
                TextEntry::make('stock')
                    ->label('Product Stock')
                    ->icon('heroicon-o-archive-box')
                ])
                ->columnSpanFull(),

                Section::make('Media and Status')
                ->schema([
                ImageEntry::make('image')
                    ->label('Product Image')
                    ->disk('public'),
                TextEntry::make('price')
                    ->label('Product Price')
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-banknotes')
                    ->formatStateUsing(fn ($state) => $state === null ? '-' : 'Rp ' . number_format((float) $state, 0, ',', '.')),
                TextEntry::make('stock')
                    ->label('Product Stock')
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-archive-box'),
                IconEntry::make('is_active')
                    ->label('Is Active')
                    ->boolean(),
                IconEntry::make('is_featured')
                    ->label('Is Featured')
                    ->boolean(),
                ])
                ->columnSpanFull()
            ]);
    }
}
