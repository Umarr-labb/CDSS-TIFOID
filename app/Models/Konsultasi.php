<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    protected $table = 'konsultasi';
    protected $primaryKey = 'id_konsultasi';
    public $timestamps = false;

    protected $fillable = [
        'id_pasien', 'tanggal', 'id_penyakit_hasil', 'cf_hasil', 'status', 'catatan',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function details()
    {
        return $this->hasMany(KonsultasiDetail::class, 'id_konsultasi');
    }

    public function hasil()
    {
        return $this->hasMany(HasilDiagnosis::class, 'id_konsultasi');
    }
}