<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPerhitungan extends Model
{
    use HasFactory;

    protected $table = 'hasil_perhitungan';

    protected $fillable = [
        'hasil_bobot_id',
        'kode_kriteria',
        'nilai_asli',
        'bobot_roc',
        'nilai_terbobot',
        'hasil_s'
    ];

    protected $casts = [
        'nilai_asli' => 'float',
        'bobot_roc' => 'float',
        'nilai_terbobot' => 'float',
        'hasil_s' => 'float'
    ];

    // Relasi ke tabel hasil_bobot
    public function hasilBobot()
    {
        return $this->belongsTo(HasilBobot::class, 'hasil_bobot_id', 'id');
    }


    // Relasi ke tabel kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode');
    }

    // Scope untuk filter berdasarkan alternatif
    public function scopeByAlternatif($query, $hasilBobotId)
    {
        return $query->where('hasil_bobot_id', $hasilBobotId);
    }

    // Scope untuk filter berdasarkan kriteria
    public function scopeByKriteria($query, $kodeKriteria)
    {
        return $query->where('kode_kriteria', $kodeKriteria);
    }

    // Accessor untuk format nilai
    public function getFormattedNilaiAsliAttribute()
    {
        return number_format($this->nilai_asli, 2);
    }

    public function getFormattedBobotRocAttribute()
    {
        return number_format($this->bobot_roc, 3);
    }

    public function getFormattedNilaiTerbobotAttribute()
    {
        return number_format($this->nilai_terbobot, 4);
    }

    public function getFormattedHasilSAttribute()
    {
        return $this->hasil_s ? number_format($this->hasil_s, 4) : '-';
    }
}
