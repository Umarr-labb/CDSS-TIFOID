<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilDiagnosis extends Model
{
    protected $table = 'hasil_diagnosis';
    protected $primaryKey = 'id_hasil';
    public $timestamps = false;

    protected $fillable = [
        'id_konsultasi', 'id_penyakit', 'cf_gabungan', 'persentase', 'kategori',
    ];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'id_penyakit');
    }
}