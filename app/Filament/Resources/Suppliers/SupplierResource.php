<?php

namespace App\Filament\Resources\Suppliers;

use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Suppliers\Tables\SuppliersTable;
use App\Models\Supplier;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\Textarea; 
use Filament\Schemas\Components\Section; 
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Supplier';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            Section::make('Informasi Supplier')
                ->description('Masukkan data supplier perusahaan')
                ->icon('heroicon-o-building-office')
                ->schema([

                    TextInput::make('nama_perusahaan')
                        ->label('Nama Perusahaan')
                        ->placeholder('Contoh: PT. Sumber Makmur')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-m-building-office-2'),

                    TextInput::make('nama_kontak')
                        ->label('Nama Contact Person')
                        ->placeholder('Contoh: Budi Santoso')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-m-user'),

                    TextInput::make('telepon')
                        ->label('Nomor Telepon')
                        ->placeholder('Contoh: 08123456789')
                        ->tel()
                        ->required()
                        ->maxLength(15)
                        ->prefixIcon('heroicon-m-phone'),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->placeholder('Contoh: supplier@email.com')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-m-envelope'),

                    Textarea::make('alamat')
                        ->label('Alamat Lengkap')
                        ->placeholder('Jl. Contoh No. 123, Kota, Provinsi')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),

                ])
                ->columns(2),

            Section::make('Logo Supplier')
                ->description('Upload logo perusahaan supplier')
                ->icon('heroicon-o-photo')
                ->schema([

                    FileUpload::make('image')
                        ->label('Logo Perusahaan')
                        ->image()
                        ->directory('suppliers')
                        ->visibility('public')
                        ->imageEditor()
                        ->imagePreviewHeight('200')
                        ->loadingIndicatorPosition('left')
                        ->panelAspectRatio('2:1')
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->uploadProgressIndicatorPosition('left')
                        ->downloadable()
                        ->openable()
                        ->required(),

                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->striped()
        ->defaultSort('created_at', 'desc')
        ->columns([

            ImageColumn::make('image')
                ->label('Logo')
                ->disk('public')
                ->circular()
                ->height(60)
                ->width(60),

            TextColumn::make('nama_perusahaan')
                ->label('Perusahaan')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->icon('heroicon-m-building-office-2')
                ->color('primary'),

            TextColumn::make('nama_kontak')
                ->label('Contact Person')
                ->searchable()
                ->icon('heroicon-m-user'),

            TextColumn::make('telepon')
                ->label('Telepon')
                ->icon('heroicon-m-phone'),

            TextColumn::make('email')
                ->label('Email')
                ->icon('heroicon-m-envelope')
                ->copyable(),

            TextColumn::make('created_at')
                ->label('Ditambahkan')
                ->dateTime('d M Y')
                ->sortable()
                ->badge()
                ->color('success'),

        ])

        ->filters([

            //

        ])

        ->actions([

            ViewAction::make(),

            EditAction::make()
                ->color('warning')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Supplier berhasil diperbarui')
                ),

            DeleteAction::make()
                ->color('danger')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Supplier berhasil dihapus')
                ),

        ])

        ->bulkActions([

            BulkActionGroup::make([

                DeleteBulkAction::make(),

            ]),

        ])

        ->emptyStateHeading('Belum Ada Data Supplier')
        ->emptyStateDescription('Silakan tambahkan supplier baru')
        ->emptyStateIcon('heroicon-o-building-office');

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
            'index' => ListSuppliers::route('/'),
            'create' => CreateSupplier::route('/create'),
            'edit' => EditSupplier::route('/{record}/edit'),
        ];
    }
}
