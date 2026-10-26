<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonsultasiDetail extends Model
{
    protected $table = 'konsultasi_detail';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = ['id_konsultasi', 'id_gejala', 'nilai_cf_user'];

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'id_gejala');
    }
}