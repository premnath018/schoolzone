<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateDailyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-daily-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Get active classes (adjust this as needed)
        $activeClasses = Classes::all();

        foreach ($activeClasses as $class) {
            $students = $class->students;

            // Efficiently create attendance records using bulk insertion
            $attendances = $students->map(function ($student) use ($today, $class) {
                return [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'date' => $today,
                    'status' => 'present',
                    'created_at' => now(),  // Add current timestamp
                    'updated_at' => now(),  // Add current timestamp (assuming it's also the updated time)
                ];
            })->toArray();

            Attendance::insert($attendances);
        }    }
}
