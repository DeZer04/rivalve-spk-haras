<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KriteriaResource\Pages;
use App\Filament\Resources\KriteriaResource\RelationManagers;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

class KriteriaResource extends Resource
{
    protected static ?string $model = Kriteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'xl' => 1,
            ])
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Membuat Set Kriteria')
                        ->columns(3)
                        ->schema([
                            Forms\Components\TextInput::make('Nama Set Kriteria')
                                ->required(),
                            Forms\Components\TextInput::make('Deskripsi Set Kriteria')
                                ->required(),
                            Forms\Components\TextInput::make('Jumlah Kriteria')
                                ->numeric()
                                ->inputMode('numeric')
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $set('criteria_count', $state);
                                }),
                        ]),
                    Wizard\Step::make('Menambahkan List Kriteria')
                        ->columns(2)
                        ->schema(function (callable $get) {
                            $criteriaCount = $get('criteria_count');
                            $components = [];

                            for ($i = 0; $i < $criteriaCount; $i++) {
                                $components[] = Forms\Components\TextInput::make("kriteria_{$i}_nama")
                                    ->label("Masukkan Nama Kriteria " . ($i + 1))
                                    ->required();
                                $components[] = Forms\Components\Select::make("kriteria_{$i}_jenis")
                                    ->label("Masukkan Jenis Kriteria " . ($i + 1))
                                    ->options([
                                        'cost' => 'Cost',
                                        'benefit' => 'Benefit',
                                    ])
                                    ->required();
                            }

                            return $components;
                        }),
                    Wizard\Step::make('Pengisian Skala Kepentingan AHP')
                        ->columns([
                            'lg' => 8,
                            'sm' => 12,
                        ])
                        ->schema(function (callable $get) {
                            $criteriaCount = $get('criteria_count');
                            $components = [];

                            $criteriaNames = [];
                            for ($i = 0; $i < $criteriaCount; $i++) {
                                $criteriaNames[$i] = $get("kriteria_{$i}_nama");
                            }

                            // Mengisi matriks perbandingan berpasangan AHP
                            for ($i = 0; $i < $criteriaCount; $i++) {
                                for ($j = $i + 1; $j < $criteriaCount; $j++) {
                                    $components[] = Forms\Components\Select::make("ahp_{$i}_{$j}")
                                        ->label("Skala Kepentingan {$criteriaNames[$i]} terhadap {$criteriaNames[$j]}")
                                        ->options([
                                            1 => '1 (Sama penting)',
                                            1/2 => '1/2 (Sama penting sedikit lebih lemah)',
                                            1/3 => '1/3 (Sama penting lebih lemah)',
                                            1/4 => '1/4 (Sama penting jauh lebih lemah)',
                                            1/5 => '1/5 (Sama penting jauh lebih lemah)',
                                            1/6 => '1/6 (Sama penting sangat lemah)',
                                            1/7 => '1/7 (Sama penting sangat jauh lebih lemah)',
                                            1/8 => '1/8 (Sama penting sangat sangat lemah)',
                                            1/9 => '1/9 (Sama penting sangat sangat jauh lebih lemah)',
                                            2 => '2 (Sedikit lebih penting)',
                                            3 => '3 (Lebih penting)',
                                            4 => '4 (Lebih penting)',
                                            5 => '5 (Lebih penting)',
                                            6 => '6 (Lebih penting)',
                                            7 => '7 (Sangat penting)',
                                            8 => '8 (Sangat penting)',
                                            9 => '9 (Mutlak lebih penting)',
                                        ])
                                        ->required();
                                }
                            }

                            return $components;
                        }),
                ]),

                Section::make()
                    ->description('Panduan untuk pengisian skala kepentingan AHP.')
                    ->schema([
                        Placeholder::make('Tabel Saaty')
                            ->content('
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nilai</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Sama penting</td>
                                        </tr>
                                        <tr>
                                            <td>1/2</td>
                                            <td>Sama penting sedikit lebih lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/3</td>
                                            <td>Sama penting lebih lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/4</td>
                                            <td>Sama penting jauh lebih lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/5</td>
                                            <td>Sama penting jauh lebih lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/6</td>
                                            <td>Sama penting sangat lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/7</td>
                                            <td>Sama penting sangat jauh lebih lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/8</td>
                                            <td>Sama penting sangat sangat lemah</td>
                                        </tr>
                                        <tr>
                                            <td>1/9</td>
                                            <td>Sama penting sangat sangat jauh lebih lemah</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Sedikit lebih penting</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Lebih penting</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Lebih penting</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Lebih penting</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Lebih penting</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Sangat penting</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Sangat penting</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>Mutlak lebih penting</td>
                                        </tr>
                                    </tbody>
                                </table>
                        ')
                ])
                ->columnSpan(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('set'),
                Tables\Columns\TextColumn::make('keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
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
            'index' => Pages\ListKriterias::route('/'),
            'create' => Pages\CreateKriteria::route('/create'),
            'edit' => Pages\EditKriteria::route('/{record}/edit'),
        ];
    }
}
