<?php

namespace App\Filament\Resources\ExamGroupResource\Pages;

use App\Filament\Resources\ExamGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExamGroup extends ViewRecord
{
    protected static string $resource = ExamGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
