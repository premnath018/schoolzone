<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassesResource\Pages;
use App\Filament\Resources\ClassesResource\RelationManagers;
use App\Models\Classes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassesResource extends Resource
{
    protected static ?string $model = Classes::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification'; // Adjust icon as needed

    protected static ?string $navigationGroup = 'Class & Attendance';

    protected static ?int $navigationSort = 6; // Position after AcademicYearResource

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('class_name')->label('Class Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('grade_level')
                    ->options([
                        1 => 'Grade 1',
                        2 => 'Grade 2',
                        3 => 'Grade 3',
                        4 => 'Grade 4',
                        5 => 'Grade 5',
                        6 => 'Grade 6',
                        7 => 'Grade 7',
                        8 => 'Grade 8',
                        9 => 'Grade 9',
                        10 => 'Grade 10',
                        11 => 'Grade 11',
                        12 => 'Grade 12',
                    ])->native(false)->searchable()->preload()
                    ->required(),
                Forms\Components\TextInput::make('section')
                    ->maxLength(255)->required(),
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'first_name')->native(false)->searchable()->preload()->required(),
                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')->native(false)->searchable()->preload()->required(),
                Forms\Components\TextInput::make('total_students')->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('class_name')->label('Class Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grade_level'),
                Tables\Columns\TextColumn::make('section'),
                Tables\Columns\TextColumn::make('teacher.first_name') // Display teacher name
                    ->label('Teacher'),
                Tables\Columns\TextColumn::make('academicYear.name') // Display academic year name
                    ->label('Academic Year'),
             //   Tables\Columns\TextColumn::make('total_students'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add filters as needed
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            // Add relations as needed (e.g., students, assignments)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasses::route('/create'),
            'edit' => Pages\EditClasses::route('/{record}/edit'),
        ];
    }
}
