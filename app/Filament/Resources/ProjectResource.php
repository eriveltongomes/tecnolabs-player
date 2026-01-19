<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Group; 
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid; 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Empreendimentos';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3) 
                    ->schema([
                        // --- COLUNA DA ESQUERDA (PRINCIPAL - Ocupa 2/3) ---
                        Group::make()
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Detalhes do Empreendimento')
                                    ->description('Informações básicas de identificação')
                                    ->icon('heroicon-m-identification')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nome do Projeto')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Ex: Algarve Residence'),
                                        
                                        Grid::make(2)->schema([
                                            TextInput::make('slug')
                                                ->label('URL Amigável (Slug)')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->prefix(env('APP_URL') . '/')
                                                ->maxLength(255),
                                            
                                            TextInput::make('city')
                                                ->label('Cidade')
                                                ->placeholder('Ex: Maceió - AL')
                                                ->maxLength(255),
                                        ]),
                                    ]),

                                Section::make('Capa de Entrada (Intro)')
                                    ->description('Imagem que aparece na tela de boas-vindas com o botão iniciar.')
                                    ->icon('heroicon-m-presentation-chart-line')
                                    ->schema([
                                        FileUpload::make('intro_image')
                                            ->label('Imagem de Fundo (Intro)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('intros')
                                            ->visibility('public')
                                            ->openable(false)
                                            ->downloadable(false)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Fachada Principal')
                                    ->description('Esta é a imagem que aparecerá no fundo da tela inicial (Masterplan).')
                                    ->icon('heroicon-m-photo')
                                    ->schema([
                                        FileUpload::make('facade_image')
                                            ->hiddenLabel()
                                            ->image()
                                            ->disk('public')
                                            ->directory('facades')
                                            ->visibility('public')
                                            ->imagePreviewHeight('300')
                                            ->openable(false)
                                            ->downloadable(false)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // --- COLUNA DA DIREITA (LATERAL - Ocupa 1/3) ---
                        Group::make()
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Status')
                                    ->schema([
                                        Toggle::make('active')
                                            ->label('Projeto Ativo')
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->default(true)
                                            ->helperText('Desative para ocultar este projeto do site.'),
                                    ]),

                                Section::make('Identidade Visual')
                                    ->icon('heroicon-m-paint-brush')
                                    ->collapsible()
                                    ->schema([
                                        ColorPicker::make('theme_config.primary_color')
                                            ->label('Cor Primária')
                                            ->helperText('Fundo principal'),
                                        
                                        ColorPicker::make('theme_config.secondary_color')
                                            ->label('Cor Secundária')
                                            ->helperText('Detalhes e botões'),

                                        TextInput::make('theme_config.font_family')
                                            ->label('Fonte (Google Fonts)')
                                            ->placeholder('Montserrat'),

                                        FileUpload::make('theme_config.logo_url')
                                            ->label('Logo do Projeto')
                                            ->image()
                                            ->disk('public')
                                            ->directory('logos')
                                            ->visibility('public')
                                            ->openable(false)
                                            ->avatar(), 
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('facade_image')
                    ->label('Fachada')
                    ->circular()
                    ->disk('public'),
                    
                TextColumn::make('name')
                    ->label('Nome')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city')
                    ->label('Cidade')
                    ->icon('heroicon-m-map-pin')
                    ->sortable(),

                ColorColumn::make('theme_config.primary_color')
                    ->label('Cor'),

                IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Atualizado')
                    ->color('gray')
                    ->size(TextColumn\TextColumnSize::ExtraSmall),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UnitsRelationManager::class,
            RelationManagers\MediaCategoriesRelationManager::class,
            RelationManagers\MediaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}