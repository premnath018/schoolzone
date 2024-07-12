<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section  as InfoSection;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;


class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'School Management';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')->schema([
                    TextInput::make('first_name')->label('First Name')->required(),
                    TextInput::make('last_name')->label('Last Name')->required(),
                    TextInput::make('email')->label('Email')->required(),
                    TextInput::make('phone')->label('Phone Number')->required(),
                    DatePicker::make('date_of_birth')->label('Date of Birth')->required()->native(false),
                    Select::make('gender')->label('Gender')->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])->required()->native(false),
                    Textarea::make('address')->label('Address')->columnSpanFull()->required(),
                    TextInput::make('city')->label('City')->required(),
                    TextInput::make('state')->label('State')->required(),
                    TextInput::make('zip')->label('Zip Code')->required(),
                ])->columns(3),
                Section::make('Contact Details')->schema([
                    TextInput::make('phone')->label('Phone Number')->required(),
                    TextInput::make('emergency_contact_name')->label('Emergency Contact Name')->required(),
                    TextInput::make('emergency_contact_phone')->label('Emergency Contact Phone')->required(),
                ])->columns(3),
                Section::make('Professional Information')->schema([
                    Select::make('subject_id')->label('Subject')->relationship('subject','name')->searchable()->preload()->native(0)->required(),
                    DatePicker::make('joining_date')->label('Joining Date')->required()->native(false),
                    TextInput::make('experience')->label('Experience (Years)')->numeric()->required(),
                    TextInput::make('salary')->label('Salary')->prefix('₹')->required(),
                    Select::make('employment_status')->label('Employment Status')->options([
                        'Full-time' => 'Full-time',
                        'Part-time' => 'Part-time',
                    ])->required()->native(false),
                    Textarea::make('qualifications')->label('Qualifications')->required(),
                    Textarea::make('qualification_degree')->label('Degree Details'),
                    Textarea::make('responsibilities')->label('Responsibilities'),
                    Toggle::make('is_active')->label('Is Active')
                        ->onColor('success')
                        ->offColor('danger')->inline(false)
                ])->columns(3)
    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id'),
                TextColumn::make('first_name')->label('Name')->searchable(),
                TextColumn::make('Age')->getStateUsing(function (Model $teacher) 
                {  return Carbon::parse($teacher->date_of_birth)->age  ; }),
                TextColumn::make('subject.name')->label('Subject')->searchable(),
                TextColumn::make('phone')->label('Phone')->prefix('+91 ')->searchable(),

            ])


            ->filters([
                // SelectFilter::make('subject_id')
                //     ->label('Subject')
                //     ->options(Subject::all()->pluck('name', 'id'))
                //     ->query(function (Builder $query, array $data) {
                //         if (isset($data['value'])) {
                //             return $query->where('subject_id', $data['value']);
                //         }
                //         return $query;
                //     }),

                SelectFilter::make('subject')->multiple()->preload()
                ->relationship('subject', 'name')
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
            InfoSection::make('Personal Details')->schema([
                TextEntry::make('first_name')->label('First Name'),
                TextEntry::make('last_name')->label('Last Name'),
                TextEntry::make('email')->label('Email'),
                TextEntry::make('date_of_birth')->label('Date of Birth')->date(),
                TextEntry::make('gender')->label('Gender'),
                TextEntry::make('address')->label('Address'),
                TextEntry::make('city')->label('City'),
                TextEntry::make('state')->label('State'),
                TextEntry::make('zip')->label('Zip Code'),
            ])->columns(3),
            InfoSection::make('Contact Details')->schema([
                TextEntry::make('phone')->label('Phone Number'),
                TextEntry::make('emergency_contact_name')->label('Emergency Contact Name'),
                TextEntry::make('emergency_contact_phone')->label('Emergency Contact Phone'),
            ])->columns(3),
            InfoSection::make('Professional Information')->schema([
                TextEntry::make('subject_id')->label('Subject')
                  ->getStateUsing(function (Model $teacher) 
                  {     return Subject::find($teacher->subject_id)->name ?? 'Unknown Subject'; }),
                TextEntry::make('joining_date')->label('Joining Date')->date(),
                TextEntry::make('experience')->label('Experience (Years)'),
                TextEntry::make('salary')->label('Salary')->prefix('₹ '),
                TextEntry::make('employment_status')->label('Employment Status'),
                TextEntry::make('qualifications')->label('Qualifications')
                ->getStateUsing(function (Model $teacher)  { return $teacher->qualifications ?  $teacher->qualifications : 'No Qualifications Details'; } ),    
                TextEntry::make('qualification_degree')->label('Degree Details')
                ->getStateUsing(function (Model $teacher)  { return $teacher->qualification_degree ?  $teacher->qualification_degree : 'No Degree Details'; } ),    
                TextEntry::make('responsibilities')->label('Responsibilities')
                ->getStateUsing(function (Model $teacher)  { return $teacher->responsibilities ?  $teacher->responsibilities : 'No Responsibility Details'; } ),    

                TextEntry::make('is_active')->label('Is Active')
                ->getStateUsing(function (Model $teacher)  { return $teacher->is_active ?  'Active' : 'Unactive'; } ),    

            ])->columns(3)

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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view' => Pages\ViewTeacher::route('/{record}'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
