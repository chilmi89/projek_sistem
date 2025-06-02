<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilBobot extends Model
{
    use HasFactory;

    protected $table = 'hasil_bobot';

    protected $fillable = [
        'student_id',
        'nama',
        'c1',
        'c2',
        'c3',
        'c4',
        'c5',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function hasilPerhitungans()
    {
        return $this->hasMany(HasilPerhitungan::class , 'hasil_bobot_id', 'id');
    }

}
