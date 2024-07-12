<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateExam extends CreateRecord
{
    protected static string $resource = ExamResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    
protected function handleRecordCreation(array $data): Model
{
    $exam = static::getModel()::create($data);
    $class = $exam->class; // Access related class instance
    $students = $class->students; 
    foreach ($students as $student) {
        $student->marks()->create([
            'student_id' => $student->id,
            'exam_id' => $exam->id,
            'mark' => null,
        ]);
    }
    return $exam;
}



}
