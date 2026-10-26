<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    protected $table = 'gejala';
    protected $primaryKey = 'id_gejala';
    public $timestamps = false;

    protected $fillable = [
        'kode_gejala', 'nama_gejala', 'jenis', 'keterangan',
    ];
}