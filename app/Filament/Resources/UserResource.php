<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Roles;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Admin Management';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                TextInput::make('name')->required()->helperText('Your full name here, including any middle names.')->placeholder('Jai Surya'),
                TextInput::make('email')->required()->email()->disabledOn('edit')->placeholder('surya@vrinvrog.com')->helperText('Your personel email id.'), // Disable email in both forms
                TextInput::make('password')->required()->password()->hidden()->rules('required')->hiddenOn('edit')->rules('confirmed', 'create'),  // ->dehydrateStateUsing(fn ($state) => Hash::make($state)), // Add password only for create form
                TextInput::make('password_confirmation')->required()->hiddenOn('edit'),
                Select::make('role_id')
                ->label('Role')
                ->options(Roles::all()->pluck('role_name', 'id'))->native(false),
                ])->columns(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->icon('heroicon-m-envelope')->iconColor('primary')->searchable(),
                TextColumn::make('role_name')
            ->label('Role')
            ->getStateUsing(function (Model $user) {
                    return $user->role->role_name;
                
            }),
                TextColumn::make('created_at')->dateTime(),   
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
