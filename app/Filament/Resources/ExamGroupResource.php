<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamGroupResource\Pages;
use App\Filament\Resources\ExamGroupResource\RelationManagers;
use App\Models\ExamGroup;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamGroupResource extends Resource
{
    protected static ?string $model = ExamGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $navigationGroup = 'Exam & Marks';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create Exam Group')
                ->description('Create Exam Group and map it with the corresponding Exams')
                ->schema([
                    TextInput::make('exam_group_name')->label('Exam Group Name')->required(),
                    TextInput::make('sheet_title')->label('Mark Sheet Title')->required(),
                    TextInput::make('description')->label('Description')->required(),
                    TextInput::make('grade_level')->label('Grade')->numeric()->rules(['min:1', 'max:12'])->required(),
                    Select::make('exam_type_id')->label('Exam Type')->relationship('examType','exam_type_name')->required()
                ])->columns(2)           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->rowIndex()->label(''),
                TextColumn::make('exam_group_name')->label('Exam Group Name'),
                TextColumn::make('sheet_title')->label('Mark Sheet Title'),
                TextColumn::make('description')->label('Description')->wrap(),
                TextColumn::make('grade_level')->label('Grade'),
                TextColumn::make('examType.exam_type_name')->label('Exam Type'), // Assuming relationship with ExamType model
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime('H:i:s - d M Y')
                ->sortable()
                ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListExamGroups::route('/'),
            'map' => Pages\MapExams::route('/map-exams')
       //     'create' => Pages\CreateExamGroup::route('/create'),
       //     'view' => Pages\ViewExamGroup::route('/{record}'),
       //     'edit' => Pages\EditExamGroup::route('/{record}/edit'),
        ];
    }
}
