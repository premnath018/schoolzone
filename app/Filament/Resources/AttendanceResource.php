<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Classes;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Text;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section  as InfoSection;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    protected static ?string $navigationGroup = 'Class & Attendance';

    protected static ?int $navigationSort = 12; // Position after AcademicYearResource

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Assuming relationships are defined for 'student' and 'class'
                Select::make('student_id')->relationship('student', 'first_name')->required()->disabled(),
                Select::make('class_id')->relationship('class', 'class_name')->required()->disabled(),
                DatePicker::make('date')->required()->native(false),
                Select::make('status')->options([
                    'present' => 'Present',
                    'absent' => 'Absent',
                    'late' => 'Late',
                ])->searchable()->preload()->required()->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                ->rowIndex(),
                Tables\Columns\TextColumn::make('student.first_name')
                ->numeric()
                ->searchable(),
                Tables\Columns\TextColumn::make('class.class_name')
                ->label('Class')
                ->searchable(),
                Tables\Columns\TextColumn::make('date')
                ->label('Date')->dateTime('d M Y')
                ->searchable(),
                Tables\Columns\TextColumn::make('status')
                ->color(fn (string $state): string => match ($state) {
                    'Late' => 'warning',
                    'Present' => 'success',
                    'Absent' => 'danger',
                })
                ->icon(fn (string $state): string => match ($state){
                    'Late' => 'heroicon-s-exclamation-triangle',
                    'Present' => 'heroicon-s-check-badge',
                    'Absent' => 'heroicon-s-x-circle',
                })
                ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime('H:i:s - d M Y')
                ->sortable()
                ->toggleable(),
            ])->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('Class')->multiple()->preload()
                ->relationship('class', 'class_name')
                ->indicateUsing(function (array $data): ?string {
                    if (empty($data['class'])) {
                        return null;
                    }
            
                    $selectedClasses = Classes::whereIn('id', $data['class'])
                        ->pluck('class_name')
                        ->implode(', ');
            
                    return "Classes: $selectedClasses";
                })
                ,
                Filter::make('grade_level')
                ->form([
                    Select::make('grade_level')
                    ->options(Classes::select('grade_level')->distinct()->get()->pluck('grade_level', 'grade_level'))
                    ->native(false)->placeholder('All'),
                 ])
                 ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['grade_level'], function (Builder $query) use ($data) {
                            $query->whereHas('student.class', function (Builder $query) use ($data) {  // Use relationship to filter
                                $query->where('grade_level', $data['grade_level']);
                            });
                        });
                })
                ->indicateUsing(function (array $data): ?string {
                    if (!$data['grade_level']) {
                        return null;
                    }
                    return 'Marks From Grade Level: ' . $data['grade_level'];  // Display grade level in indication
                }),
                Filter::make('Date')
                ->form([
                    DatePicker::make('date')->format('Y-m-d')->required(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['date'], function (Builder $query) use ($data) {
                        $query->whereDate('date', $data['date']);
                    });
                })
                ->indicateUsing(function (array $data): ?string {
                    if (!$data['date']) {
                        return null;
                    }
                    return "Date: " . Carbon::parse($data['date'])->format('d M Y');
                }),
    
            Filter::make('Status')
                ->form([
                    Select::make('status')->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                    ])->searchable()->preload()->required()->native(false),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['status'], function (Builder $query) use ($data) {
                        $query->where('status', $data['status']);
                    });
                })
                ->indicateUsing(function (array $data): ?string {
                    if (!$data['status']) {
                        return null;
                    }
                    return "Status: " . ucfirst($data['status']);
                }),
            ],layout:FiltersLayout::AboveContentCollapsible)
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

    public static function infoList(InfoList $infoList): InfoList
    {
        return $infoList
            ->schema([
                InfoSection::make()
                ->columns(8)->schema([
                TextEntry::make('student.first_name'),
                TextEntry::make('class.class_name'),
                TextEntry::make('date')->date('d M Y'),
                TextEntry::make('status'),
                TextEntry::make('created_at'),
                TextEntry::make('updated_at'),
                ])
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
           // 'view' => Pages\ViewAttendance::route('/{record}'),
          //  'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
