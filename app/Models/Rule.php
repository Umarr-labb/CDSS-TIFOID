<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rule';
    protected $primaryKey = 'id_rule';
    public $timestamps = false;

    protected $fillable = [
        'kode_rule', 'id_penyakit', 'cf_pakar', 'keterangan',
    ];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class, 'id_penyakit');
    }

    public function details()
    {
        return $this->hasMany(RuleDetail::class, 'id_rule');
    }
}