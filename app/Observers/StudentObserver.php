<?php

namespace App\Observers;

use App\Models\Student;

use App\Models\HasilBobot;
use Illuminate\Support\Facades\Log;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        $mapped = $student->getMappedScores();
        Log::info('Mapped scores for ' . $student->nama . ': ', $mapped);
        HasilBobot::create($mapped);
    }

    public function updated(Student $student): void
    {
        $mapped = $student->getMappedScores();
        Log::info('Updated mapped scores for ' . $student->nama . ': ', $mapped);
        HasilBobot::where('nama', $student->nama)->update($mapped);
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
