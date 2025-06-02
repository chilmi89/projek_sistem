<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\AlternatifImport;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Maatwebsite\Excel\Facades\Excel;

class AlternatifController extends Controller
{
    /** • GET /alternatif/import */
    public function index()
    {
        $kriterias   = Kriteria::orderBy('kode')->pluck('kode');
        $alternatifs = Alternatif::with('nilai')->get();

        return view('guru.alternatif', compact('alternatifs', 'kriterias'));
    }

    /**
     * POST /alternatif/import
     * Proses import file Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AlternatifImport, $request->file('file'));
            return redirect()->route('alternatif.index')->with('success', 'Data siswa berhasil di-import 🚀');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimport: ' . $e->getMessage());
        }
    }
}
