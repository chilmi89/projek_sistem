<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubKriteria;

class SubKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subKriterias = SubKriteria::all();
        foreach ($subKriterias as $subKriteria) {
            $range = explode(' - ', $subKriteria->sub_kriteria);
            if (count($range) == 2) {
                $subKriteria->update([
                    'nilai_min' => (int)$range[0],
                    'nilai_max' => (int)$range[1],
                ]);
            }
        }
    }
}
