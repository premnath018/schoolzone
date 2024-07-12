<?php

namespace App\Filament\Resources\ExamGroupResource\Pages;

use App\Filament\Resources\ExamGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamGroup extends EditRecord
{
    protected static string $resource = ExamGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
