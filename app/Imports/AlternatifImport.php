<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Student;
class AlternatifImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // Lewati baris pertama (biasanya header)
        $rows = $collection->skip(1);

        $data = [];
        foreach ($rows as $row) {
            $siswa = [
                'nama' => $row[1],
                'mtk_um' => $row[2],
                'ipa' => $row[3],
                'ips' => $row[4],
                'b_ing' => $row[5],
                'tes_iq' => $row[6],
            ];

            // Simpan ke database
            Student::create($siswa);


        }
    }
}
