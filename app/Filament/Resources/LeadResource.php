<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Empreendimento')
                    ->disabled(), // Geralmente não editamos isso, vem do site
                Forms\Components\TextInput::make('name')->label('Nome')->required(),
                Forms\Components\TextInput::make('email')->email()->label('E-mail'),
                Forms\Components\TextInput::make('phone')->tel()->label('WhatsApp'),
                Forms\Components\Textarea::make('message')->label('Mensagem')->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'Novo',
                        'contacted' => 'Em Atendimento',
                        'closed' => 'Fechado',
                        'lost' => 'Perdido',
                    ])
                    ->default('new')
                    ->label('Status do Atendimento'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Data'),
                Tables\Columns\TextColumn::make('name')->searchable()->label('Cliente'),
                Tables\Columns\TextColumn::make('project.name')->sortable()->label('Interesse em'),
                Tables\Columns\TextColumn::make('phone')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'success',
                        'contacted' => 'warning',
                        'closed' => 'info',
                        'lost' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')->relationship('project', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
