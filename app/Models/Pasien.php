<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'no_rm', 'nama_pasien', 'jenis_kelamin',
        'tanggal_lahir', 'umur', 'alamat', 'no_telp',
    ];

    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'id_pasien');
    }
}