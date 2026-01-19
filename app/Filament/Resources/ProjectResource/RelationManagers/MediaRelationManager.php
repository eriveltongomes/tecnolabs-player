<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MediaRelationManager extends RelationManager
{
    protected static string $relationship = 'media';
    protected static ?string $title = 'Galeria e Mídias';
    protected static ?string $modelLabel = 'Mídia';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)->schema([
                    Select::make('media_category_id')
                        ->label('Categoria / Álbum')
                        ->relationship('category', 'name')
                        ->preload()
                        ->searchable()
                        ->required(),

                    Select::make('file_type')
                        ->label('Tipo de Mídia')
                        ->options([
                            'image' => 'Imagem',
                            'video' => 'Vídeo (MP4)',
                            '360'   => 'Tour 360 (ZIP do Pano2VR)',
                        ])
                        ->default('image')
                        ->live()
                        ->required(),

                    FileUpload::make('path')
                        ->label(fn (Forms\Get $get) => $get('file_type') === '360' ? 'Arquivo ZIP (Padrão)' : 'Arquivo de Mídia')
                        ->helperText(fn (Forms\Get $get) => $get('file_type') === '360' ? 'Atenção: Use apenas formato .ZIP (Não use .7z ou .rar). O index.html deve estar dentro.' : '')
                        // CORREÇÃO DO UPLOAD ZIP: Adicionados MIME types extras para compatibilidade Windows
                        ->acceptedFileTypes([
                            'image/jpeg', 
                            'image/png', 
                            'image/webp', 
                            'video/mp4', 
                            'application/zip', 
                            'application/x-zip', 
                            'application/x-zip-compressed',
                            'multipart/x-zip', 
                            'application/octet-stream' // Zips do Windows as vezes vem assim
                        ])
                        ->disk('public')
                        ->directory('project-media')
                        ->maxSize(1024000) // 1GB (1024 * 1000)
                        ->required(fn (Forms\Get $get) => empty($get('description'))),
                    
                    TextInput::make('description') 
                        ->label('Link Externo (Opcional)')
                        ->placeholder('https://matterport.com/...')
                        ->visible(fn (Forms\Get $get) => $get('file_type') === '360')
                        ->helperText('Se preencher, o ZIP será ignorado.'),
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Stack::make([
                    // 1. SE FOR IMAGEM: MOSTRA A FOTO
                    ImageColumn::make('path')
                        ->disk('public')
                        ->height('180px')
                        ->width('100%')
                        ->visible(fn ($record) => $record?->file_type === 'image')
                        ->extraImgAttributes(['class' => 'object-cover rounded-t-lg']),

                    // 2. SE NÃO FOR IMAGEM (VÍDEO/TOUR): MOSTRA UM ÍCONE GRANDE
                    TextColumn::make('placeholder_icon')
                        ->default(fn ($record) => match($record?->file_type) {
                            'video' => '🎬',
                            '360' => '🔄',
                            default => '📁'
                        })
                        ->visible(fn ($record) => $record?->file_type !== 'image')
                        ->extraAttributes(['class' => 'h-[180px] w-full flex items-center justify-center bg-gray-100 text-6xl rounded-t-lg select-none']),

                    // RODAPÉ DO CARD
                    Stack::make([
                        TextColumn::make('category.name')->badge()->color('info')->icon('heroicon-m-folder'),
                        TextColumn::make('file_type')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'image' => '📷 Foto', 'video' => '🎬 Vídeo', '360' => '🔄 Tour 360', default => $state,
                            })
                            ->color('gray')->size(TextColumn\TextColumnSize::ExtraSmall),
                    ])->space(2)->extraAttributes(['class' => 'p-3 bg-white dark:bg-gray-800 rounded-b-lg border-x border-b dark:border-gray-700']),
                ])->space(0),
            ])
            ->headerActions([
                Tables\Actions\Action::make('upload_multiple')
                    ->label('Upload Múltiplo (Fotos)')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Select::make('media_category_id')
                            ->label('Álbum')
                            ->options(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->mediaCategories->pluck('name', 'id'))
                            ->required(),
                        FileUpload::make('files')
                            ->label('Imagens')
                            ->multiple()
                            ->image() // Upload múltiplo continua restrito a imagens
                            ->disk('public')
                            ->directory('project-media')
                            ->maxFiles(50)
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        foreach ($data['files'] as $filePath) {
                            $livewire->getOwnerRecord()->media()->create([
                                'media_category_id' => $data['media_category_id'],
                                'file_type' => 'image',
                                'path' => $filePath,
                            ]);
                        }
                        \Filament\Notifications\Notification::make()->title('Fotos enviadas!')->success()->send();
                    }),

                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Mídia / Tour')
                    ->after(function ($record) {
                        // Lógica de descompactação do ZIP
                        if ($record->file_type === '360' && $record->path && pathinfo($record->path, PATHINFO_EXTENSION) === 'zip') {
                            $zipPath = Storage::disk('public')->path($record->path);
                            $extractPath = Storage::disk('public')->path('tours/' . $record->id);
                            
                            $zip = new ZipArchive;
                            if ($zip->open($zipPath) === TRUE) {
                                if (!is_dir($extractPath)) {
                                    mkdir($extractPath, 0755, true);
                                }
                                $zip->extractTo($extractPath);
                                $zip->close();
                                \Filament\Notifications\Notification::make()->title('Tour Descompactado!')->success()->send();
                            } else {
                                \Filament\Notifications\Notification::make()->title('Erro ao descompactar')->danger()->send();
                            }
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->after(function ($record) {
                        if($record->file_type === '360') {
                            Storage::disk('public')->deleteDirectory('tours/' . $record->id);
                        }
                    }),
            ])
            ->bulkActions([ Tables\Actions\DeleteBulkAction::make() ]);
    }
}