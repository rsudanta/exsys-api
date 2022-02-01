<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'hasil_cf',
        'id_penyakit',
        'id_user',
    ];

    protected $table = 'hasil';

    public function penyakit()
    {
        return $this->hasOne(Penyakit::class, 'id', 'id_penyakit');
    }

    // public function getNamaPenyakitAttribute()
    // {
    //     return $this->penyakit;
    // }

    // protected $appends = ['nama_penyakit'];
}
