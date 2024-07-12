<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarkResource\Pages;
use App\Filament\Resources\MarkResource\RelationManagers;
use App\Models\Classes;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\Layout as AttributesLayout;
use SebastianBergmann\CodeCoverage\Test\TestSize\Large;

class MarkResource extends Resource
{
    protected static ?string $model = Mark::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Exam & Marks';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student.name')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('exam_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('mark')
                    ->numeric()
                    ->default(0.00),
            ]);
    }

    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.class.class_name')
                    ->label('Student Class')
                    ->searchable(),
                Tables\Columns\TextColumn::make('exam.exam_name')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('exam.examGroup.examGroup.exam_group_name')->label('Group Name')
                ->numeric()
                ->searchable(),
                Tables\Columns\TextInputColumn::make('mark')->rules(['decimal:1','min:0.00','max:100.00'])->disabled(0)
                    ->sortable()->summarize([
                        Average::make(),
                        Sum::make(),
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('H:i:s d-m-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('H:i:s d-m-Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('student_id')
                ->form([
                Select::make('student_id')
                ->label('Filter By Student Name')
                ->options(Student::select('id', 'first_name', 'last_name')
                    ->get()
                    ->pluck('first_name', 'id'))
                ->native(false)
                ->searchable()
                ->placeholder('All'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                return $query->when($data['student_id'], function (Builder $query) use ($data) {
                    $query->where('student_id', $data['student_id']);
                });
                })
                ->indicateUsing(function (array $data): ?string {
                if (! $data['student_id']) {
                    return null;
                }
                $studentName = Student::find($data['student_id'])->first_name . ' ' . Student::find($data['student_id'])->last_name;
                return 'Marks for Student: ' . $studentName;
                }),
                Filter::make('class_name')
                ->form([
                    Select::make('class_id')->label('Filter By Class')
                    ->options(Classes::select('id', 'class_name')
                    ->get()
                    ->pluck('class_name', 'id'))
                    ->native(false)->placeholder('All')->searchable(),
                 ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->
                    when($data['class_id'], function (Builder $query) use ($data) {
                        $query->whereHas('student', function (Builder $query) use ($data) {
                            $query->where('class_id', $data['class_id']);
                        });
                    });
                })->indicateUsing(function (array $data): ?string {
                    if (! $data['class_id']) {
                        return null;
                    }
                    $className = Classes::find($data['class_id'])->class_name;  // Retrieve class name
                    return 'Marks From Class : ' .$className;
                }),
                Filter::make('exam_name')
                ->form([
                    Select::make('exam_id')->label('Filter By Exam Name')
                        ->options(Exam::select('id', 'exam_name')
                                    ->get()
                                    ->pluck('exam_name', 'id'))
                        ->native(false)->placeholder('All')->searchable(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['exam_id'], function (Builder $query) use ($data) {
                        $query->where('exam_id', $data['exam_id']);
                    });
                })
                ->indicateUsing(function (array $data): ?string {
                    if (! $data['exam_id']) {
                        return null;
                    }
                    $examName = Exam::find($data['exam_id'])->exam_name;  // Retrieve exam name
                    return 'Marks From Exam: ' . $examName;
                }),
                Filter::make('marks_above')
                ->form([
                    TextInput::make('mark')
                        ->numeric()    
                        ->label('Marks Above')
                        ->rules(['min:0.00','max:100.00'])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $data['mark']
                        ? $query->where('mark', '>', (float) $data['mark'])  // Apply filter if mark is set
                        : $query;  // Otherwise, return the query unmodified
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['mark'] ? "Marks Above: {$data['mark']}" : null;
                }),
                Filter::make('marks_below')
                ->form([
                    TextInput::make('mark')
                        ->numeric()    
                        ->label('Marks Below')
                        ->rules(['min:0.00','max:100.00'])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $data['mark']
                        ? $query->where('mark', '<', (float) $data['mark'])  // Apply filter if mark is set
                        : $query;  // Otherwise, return the query unmodified
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['mark'] ? "Marks Below: {$data['mark']}" : null;
                })

          
            ],layout:FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultGroup('exam.examGroup.examGroup.exam_group_name');
    }

    public static function getRelations(): array
    {
        return [
            
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarks::route('/'),
            'create' => Pages\CreateMark::route('/create'),
            'view' => Pages\ViewMark::route('/{record}'),
            'edit' => Pages\EditMark::route('/{record}/edit'),
        ];
    }
}
