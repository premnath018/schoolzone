<?php

namespace App\Filament\Resources\ExamGroupResource\Pages;

use App\Filament\Resources\ExamGroupResource;
use App\Models\Exam;
use App\Models\ExamGroup;
use App\Models\ExamMap;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class MapExams extends Page implements HasForms , HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];

    protected static string $resource = ExamGroupResource::class;

    protected static string $view = 'filament.resources.exam-group-resource.pages.map-exams';

    protected static ?string $title = 'Map Exams';
 
    protected function paginateTableQuery(Builder $query): Paginator
    {
    return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ExamMap::query())
            ->columns([
                TextColumn::make('')
                ->rowIndex(),
                TextColumn::make('examGroup.exam_group_name')->label('Exam Group'),
                TextColumn::make('exam.exam_name')->label('Exam Name'),
                TextColumn::make('exam.exam_date')->date('d M Y')->label('Exam Date'),
                TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                ->dateTime('H:i:s - d M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false),  
            ])  
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])->paginated([5]);
    }



    public function mount()
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('exam_group_name')
                ->label('Exam Group Name')
                ->required()
                ->options(ExamGroup::all()->pluck('exam_group_name', 'id'))->native(false),
                Repeater::make('exams')
                ->label('Exams')
                ->required()
                ->deletable(false)
                ->reorderable(false)
                ->schema([
                    Select::make('exam_name')
                        ->label('Exam Name')
                        ->required()
                        ->options(Exam::all()->pluck('exam_name', 'id'))
                        ->native(false),
            ]),
            ])->statePath('data');
    }

    public function getFormActions():array
    {
        return [
            Action::make('save')->label('Save Exam Group')->submit('save'),
            Action::make('cancel')->url('/exam-groups')->color('gray'),     
        ];
    }

    public function save(){
        try{
            $data = $this->form->getState();
            foreach ($data['exams'] as $exam) {
                ExamMap::create([
                    'exam_group_id'=> $data['exam_group_name'],
                    'exam_id' => $exam['exam_name'] 
                ]);
            }
        }
        catch(Halt $ex){
            return;
        }
        Notification::make()->title('Exams Mapped')->success()->send();
    }


}
