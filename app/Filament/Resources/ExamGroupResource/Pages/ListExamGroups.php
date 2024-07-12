<?php

namespace App\Filament\Resources\ExamGroupResource\Pages;

use App\Filament\Resources\ExamGroupResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListExamGroups extends ListRecords
{
    protected static string $resource = ExamGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewMap')  // Use a descriptive label for clarity
            ->label('Map Exams')
            ->url('exam-groups/map-exams')  // Assuming no route parameters are needed
            ->icon('heroicon-o-arrows-pointing-in')  // Optional: Add an icon
            ->color('warning'), 
            Actions\CreateAction::make()->label('Create Exam Group'),
        ];
    }
}
