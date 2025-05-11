<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'mata_pelajaran';

    // Tentukan primary key jika bukan 'id'
    protected $primaryKey = 'id';

    // Jika primary key bukan auto-increment, set false
    public $incrementing = true;

    // Jika primary key bukan integer, tentukan tipe data
    protected $keyType = 'int';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'nama_mapel',
        'bobot',
        'deskripsi',
    ];

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mata_pelajaran_id', 'id');
    }
}
