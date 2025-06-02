<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiKelas extends Model
{
    use HasFactory;
    protected $fillable = ['hasil_bobot_id', 'kelas', 'status_alokasi'];

    public function hasilBobot()
    {
        return $this->belongsTo(HasilBobot::class);
    }
}
