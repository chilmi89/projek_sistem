<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuotaKelas extends Model
{
    use HasFactory;

    protected $table = 'kuota_kelas';

    protected $fillable = [
        'kode',
        'nama_kriteria',
        'jumlah_kelas',
        'kapasitas_per_kelas',
    ];

    protected $casts = [
        'jumlah_kelas' => 'integer',
        'kapasitas_per_kelas' => 'integer',
    ];

    // Accessor untuk total kapasitas
    public function getTotalKapasitasAttribute()
    {
        return $this->jumlah_kelas * $this->kapasitas_per_kelas;
    }

    // Relasi dengan kriteria jika diperlukan
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode', 'kode');
    }
}
