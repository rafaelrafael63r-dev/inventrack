<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput; 
use Filament\Forms\Components\Textarea; 
use Filament\Schemas\Components\Section; 
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Category';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

        Section::make('Informasi Kategori')
            ->description('Masukkan data kategori inventaris')
            ->icon('heroicon-o-tag')
            ->schema([

                TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->placeholder('Contoh: Elektronik, Furniture, ATK')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->prefixIcon('heroicon-m-tag')
                    ->autofocus(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('Jelaskan singkat tentang kategori ini')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

            ])
            ->columns(2),

        Section::make('Foto Kategori')
            ->description('Upload gambar kategori')
            ->icon('heroicon-o-photo')
            ->schema([

                FileUpload::make('image')
                    ->label('Foto Kategori')
                    ->image()
                    ->directory('categories')
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
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(60)
                    ->width(60),

                TextColumn::make('nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-tag')
                    ->color('primary'),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi),

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
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Kategori berhasil diperbarui')
                    ),

                DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Kategori berhasil dihapus')
                    ),

            ])

            ->bulkActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ])

            ->emptyStateHeading('Belum Ada Data Kategori')
            ->emptyStateDescription('Silakan tambahkan kategori baru untuk inventaris')
            ->emptyStateIcon('heroicon-o-folder-open');
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
