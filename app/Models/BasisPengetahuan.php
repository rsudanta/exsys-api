<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasisPengetahuan extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nilai_cf',
        'mb',
        'md',
        'id_penyakit',
        'id_gejala',
    ];

    protected $table = 'basis_pengetahuan';

    public function penyakit()
    {
        return $this->hasOne(Penyakit::class, 'id', 'id_penyakit');
    }
    public function gejala()
    {
        return $this->hasOne(Gejala::class, 'id', 'id_gejala');
    }
}
