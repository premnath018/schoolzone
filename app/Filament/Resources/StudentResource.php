<?php
namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section  as InfoSection;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Enums\FiltersLayout;
use App\Models\Classes;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle'; 

    protected static ?string $navigationGroup = 'School Management';

    protected static ?int $navigationSort = 7; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    // Personal Details
                Forms\Components\TextInput::make('first_name')->label('First Name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')->label('Last Name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('middle_name')->label('Middle Name')
                ->maxLength(255),
            Forms\Components\DatePicker::make('date_of_birth')->label('Date of Birth')->native(false)
                ->required(),
            Forms\Components\Select::make('gender')
                ->options([
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other',
                ])->native(false)
                ->required(),

            // Address and Contact Information
            Forms\Components\Textarea::make('address')->label('Address')
                ->maxLength(255),
            Forms\Components\TextInput::make('phone_number')->label('Phone Number')
                ->numeric()->maxLength(10)
                ->required(),
            Forms\Components\TextInput::make('email_address')->label('Email Address')
                ->unique()->email()
                ->required(),

            // Class and Enrollment Information
            Forms\Components\Select::make('class_id')
                ->relationship('class', 'class_name')
                ->native(false)
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\DatePicker::make('admission_date')->label('Admission Date')->native(false)->default(today())
                ->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                    'Graduated' => 'Graduated',
                ])->native(false)
                ->required(),

            // Emergency Contact
            Forms\Components\TextInput::make('emergency_contact_name')->label('Emergency Contact Name')
                ->maxLength(255),
            Forms\Components\TextInput::make('emergency_contact_number')->label('Emergency Contact Number')
            ->numeric()->maxLength(10)->required(),

            // Medical Information (Optional)
            Forms\Components\TextArea::make('medical_conditions')->label('Medical Conditions')
                ->nullable(),
                ])->columns(2)
                
                ]);
    }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('index')
                    ->rowIndex(),
                    Tables\Columns\TextColumn::make('first_name')->label('First Name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('last_name')->label('Last Name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('class.class_name') // Display class name
                        ->label('Class'),
                    Tables\Columns\TextColumn::make('grade_level'),
                    Tables\Columns\TextColumn::make('admission_date')
                        ->date(),
                    Tables\Columns\TextColumn::make('status'),
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
                SelectFilter::make('Class')->multiple()->preload()
                ->relationship('class', 'class_name'),
                Filter::make('grade_level')
                ->form([
                    Select::make('grade_level')
                    ->options(Classes::select('grade_level')->distinct()->get()->pluck('grade_level', 'grade_level'))
                    ->native(false)->placeholder('All'),
                 ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['grade_level'],
                            fn (Builder $query, $data): Builder => $query->where('grade_level', '=', $data),
                        );
                        })
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
    
    // public static function getRelations(): array
    // {
    //     return [
          
    //     ];
    // }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
    
            // Additional pages (optional)
            // Pages\ViewStudentExams::route('/{record}/exams'),
            // Pages\ViewStudentMarks::route('/{record}/marks'),
        ];
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('Personal Details')->schema([
                    TextEntry::make('first_name')->label('First Name'),
                    TextEntry::make('last_name')->label('Last Name'),
                    TextEntry::make('middle_name')->label('Middle Name'),
                    TextEntry::make('date_of_birth')->label('Date of Birth')->date(),
                    TextEntry::make('gender')->label('Gender'),
                    TextEntry::make('address')->label('Address'),
                    TextEntry::make('phone_number')->label('Phone Number'),
                    TextEntry::make('email_address')->label('Email Address'),
                ])->columns(3),
    
                InfoSection::make('Additional Information')->schema([
                    TextEntry::make('class.class_name')->label('Class'),
                    TextEntry::make('admission_date')->label('Admission Date')
                        ->date(),
                    TextEntry::make('status')->label('Status'),
                    TextEntry::make('emergency_contact_name')->label('Emergency Contact Name')
                    ->getStateUsing(function (Model $student) {
                        return $student->emergency_contact_name ?? 'N/A';
                    }),
                TextEntry::make('emergency_contact_number')->label('Emergency Contact Number')
                    ->getStateUsing(function (Model $student) {
                        return $student->emergency_contact_number ?? 'N/A';
                    }),
                TextEntry::make('medical_conditions')->label('Medical Conditions')
                    ->getStateUsing(function (Model $student) {
                        return $student->medical_conditions ?? 'N/A';
                    }),

                ])->columns(3),
            ]);
    }
    
    }