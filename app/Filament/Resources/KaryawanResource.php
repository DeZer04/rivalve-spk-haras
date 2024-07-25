<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Grid::make(2)
                    ->schema([
                        Components\Group::make()
                            ->schema([
                                Components\TextInput::make('idkaryawan')
                                    ->label('ID Karyawan')
                                    ->required(),
                                Components\TextInput::make('nama')
                                    ->label('Nama Karyawan')
                                    ->required(),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Components\Select::make('divisi')
                                            ->options([
                                                'kantor' => 'Kantor',
                                                'amplas' => 'Amplas',
                                                'packing' => 'Packing',
                                                'metal' => 'Bengkel Metal',
                                                'assembling' => 'Assembling'
                                            ])
                                            ->required(),
                                        Components\TextInput::make('posisi')
                                            ->label('Posisi')
                                            ->required(),
                                    ])

                            ]),
                        Components\Group::make()
                            ->schema([
                                Components\FileUpload::make('foto')
                                    ->label('Foto')
                                    ->image(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('idkaryawan')
                ->label('ID Karyawan')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('divisi')
                ->label('Divisi')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('posisi')
                ->label('Posisi')
                ->sortable()
                ->searchable(),
            Tables\Columns\ImageColumn::make('foto')
                ->label('Foto')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
