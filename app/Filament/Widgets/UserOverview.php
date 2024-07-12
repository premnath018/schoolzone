<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Roles;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Filament\Infolists\Components\Section;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';



    protected function getStats(): array
    {
        return [

            
                Stat::make('Total No Subjects',Subject::count())
               
                ->chart([3, 5, 8, 1, 11, 7, 15])
                ->color('success'),
    
                Stat::make('Total No Teachers',Teacher::count())
                ->chart([3, 5, 4, 1, 13, 7, 15])
                ->color('success'),

                Stat::make('Total No Academic Year',AcademicYear::count())
                ->chart([7, 1, 17, 8, 18, 8, 20])
                ->color('success'),

                
                Stat::make('Total No Classes',Classes::count())
                ->chart([7, 1, 8, 17, 8, 18, 20])
                ->color('success'),

                Stat::make('Total No Students',Student::count())
                ->chart([3, 8, 14, 11, 18, 15, 20])
                ->color('success'),

                Stat::make('Total No Exam Type',ExamType::count())
                ->chart([3, 8, 4, 1, 8, 5, 10])
                ->color('success'),

                Stat::make('Total No Exams',Exam::count())
                ->chart([1, 3, 6, 9, 8, 11, 18])
                ->color('success'),
           
           ];
    }
}
