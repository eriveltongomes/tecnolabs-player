<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section; // <--- Importante para criar os grupos
use Filament\Tables\Filters\SelectFilter;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';
    
    // Título da Aba
    protected static ?string $title = 'Espelho de Vendas (Unidades)';

    public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Dados da Unidade')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('block')
                            ->label('Bloco / Torre')
                            ->placeholder('Ex: Torre A')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('unit_number')
                            ->label('Número da Unidade')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('floor')
                            ->label('Andar')
                            ->numeric()
                            ->required(),
                    ]),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('typology')
                            ->label('Tipologia')
                            ->placeholder('Ex: 3 Quartos')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('area')
                            ->label('Área (m²)')
                            ->numeric()
                            ->suffix('m²'),

                        Forms\Components\TextInput::make('price')
                            ->label('Preço (R$)')
                            ->numeric()
                            ->prefix('R$'),
                    ]),

                    Forms\Components\Select::make('status')
                        ->label('Status Atual')
                        ->options([
                            'available' => 'Disponível (Verde)',
                            'sold' => 'Vendido (Vermelho)',
                            'reserved' => 'Reservado (Amarelo)',
                            'blocked' => 'Bloqueado (Cinza)',
                        ])
                        ->required()
                        ->default('available'),
                    
                    Forms\Components\FileUpload::make('floorplan_image')
                        ->label('Imagem da Planta Baixa')
                        ->image()
                        ->directory('floorplans')
                        ->visibility('public')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Posição na Fachada (Mapeamento)')
                ->description('Defina as coordenadas (0 a 100%) para posicionar a unidade na foto do prédio.')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('map_x')->label('Posição X (Horizontal %)')->numeric(),
                        Forms\Components\TextInput::make('map_y')->label('Posição Y (Vertical %)')->numeric(),
                    ]),
                ])->collapsible(),
        ]);
}

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('unit_number')
                    ->label('Unidade')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success', // Verde
                        'reserved' => 'warning',  // Laranja
                        'sold' => 'danger',       // Vermelho
                        'blocked' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Disponível',
                        'reserved' => 'Reservado',
                        'sold' => 'Vendido',
                        'blocked' => 'Bloqueado',
                        default => $state,
                    }),

                TextColumn::make('typology')->label('Tipo'),
                
                TextColumn::make('price')
                    ->money('BRL')
                    ->label('Preço'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Nova Unidade'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtrar por Status')
                    ->options([
                        'available' => 'Disponível',
                        'reserved' => 'Reservado',
                        'sold' => 'Vendido',
                        'blocked' => 'Bloqueado',
                    ]),
            ]);
    }
}