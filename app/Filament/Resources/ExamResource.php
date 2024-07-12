<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Classes;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Exam & Marks';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Exam Information')->schema([
                    TextInput::make('exam_name')->label('Exam Name')->required(),
                    Select::make('class_id')
                        ->label('Class')
                        ->relationship('class', 'class_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('subject_id')
                        ->label('Subject')
                        ->relationship('subject', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    DatePicker::make('exam_date')->label('Exam Date')->required()->native(false),
                    TextInput::make('duration')->label('Duration')->required(),
                    Select::make('exam_type_code')
                    ->label('Exam Type')
                    ->relationship('examType', 'exam_type_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                    Textarea::make('description')->label('Description')->required()->columnSpanFull(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id'),
                TextColumn::make('exam_name')->label('Exam Name')->searchable(),
                TextColumn::make('class.class_name')->label('Class')->searchable(),
                TextColumn::make('subject.name')->label('Subject')->searchable(),
                TextColumn::make('exam_date')->label('Exam Date')->getStateUsing(function (Model $model) {
                    return date('d-m-Y', strtotime($model->exam_date));
                }),
                TextColumn::make('duration')->label('Duration'),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('examType.exam_type_code')->label('Exam Type')->searchable(),
            ])
            ->filters([
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

    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            InfoSection::make('Exam Details')->schema([
                TextEntry::make('exam_name')->label('Exam Name'),
                TextEntry::make('class.class_name')->label('Class'),
                TextEntry::make('subject.name')->label('Subject'),
                TextEntry::make('exam_date')
                ->label('Exam Date')
                ->getStateUsing(function (Model $model) {
                    return date('d-m-Y', strtotime($model->exam_date));
                }),
                TextEntry::make('duration')->label('Duration'),
                TextEntry::make('description')->label('Description'),
                TextEntry::make('examType.exam_type_name')->label('Exam Type'),
            ])->columns(3),
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
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'view' => Pages\ViewExam::route('/{record}'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}
