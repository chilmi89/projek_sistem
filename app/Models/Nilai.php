<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;
    protected $table = 'nilai';

    // Kolom yang boleh diisi mass assign
    protected $fillable = [
        'siswa_id',
        'mtk_um',
        'ipa',
        'ips',
        'b_ing',
        'tes_iq',
    ];

    /**
     * Relasi Nilai ke Siswa (belongsTo)
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
