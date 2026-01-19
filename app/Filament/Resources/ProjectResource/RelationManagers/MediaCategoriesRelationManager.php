<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MediaCategoriesRelationManager extends RelationManager
{
    // AQUI ESTAVA O ERRO: Mudamos de 'media_categories' para 'mediaCategories'
    protected static string $relationship = 'mediaCategories'; 

    protected static ?string $title = 'Categorias / Álbuns';
    protected static ?string $modelLabel = 'Categoria';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome da Categoria'),
                
                Forms\Components\Select::make('type')
                    ->options([
                        'gallery' => 'Galeria de Fotos',
                        'video' => 'Vídeo',
                        'masterplan' => 'Implantação',
                        '360' => 'Tour 360',
                    ])
                    ->required()
                    ->label('Tipo de Exibição'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'primary' => 'gallery',
                        'success' => 'masterplan',
                        'warning' => 'video',
                        'info' => '360',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}