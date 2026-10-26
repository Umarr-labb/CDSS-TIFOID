<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    protected $table = 'penyakit';
    protected $primaryKey = 'id_penyakit';
    public $timestamps = false;

    protected $fillable = [
        'kode_penyakit', 'nama_penyakit', 'deskripsi',
    ];

    public function rules()
    {
        return $this->hasMany(Rule::class, 'id_penyakit');
    }

    public function rekomendasi()
    {
        return $this->hasMany(Rekomendasi::class, 'id_penyakit');
    }
}