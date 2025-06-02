<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AlternatifImport;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HasilBobot;
use Illuminate\Support\Facades\Validator;

class DataSiswaController extends Controller
{
    /**
     * Display the import form and list of students.
     *
     */

    public function index(Request $request)
    {
        $students = Student::paginate(5);
        return view('guru.alternatif', [
            'students' => $students,
        ]);
    }
    public function importExcel(Request $request)
    {
        // 1. Import siswa dari Excel
        Excel::import(new AlternatifImport, $request->file('file'));

        // 2. Ambil semua siswa yang baru saja diimport
        $students = Student::all();

        // 3. Loop dan hitung nilai bobot dari tabel sub_kriteria
        foreach ($students as $student) {
            $mapped = $student->getMappedScores();

            // 4. Update atau insert ke hasil_bobot
            HasilBobot::updateOrCreate(
                ['student_id' => $student->id], // cek berdasarkan student_id
                $student->getMappedScores()     // pastikan kembalikan array dengan c1-c5
            );
        }

        return redirect()->back()->with('success', 'Data siswa berhasil di-import dan dihitung bobotnya!');
    }
}
