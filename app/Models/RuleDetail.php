<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleDetail extends Model
{
    protected $table = 'rule_detail';
    protected $primaryKey = 'id_rule_detail';
    public $timestamps = false;

    protected $fillable = ['id_rule', 'id_gejala'];

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'id_gejala');
    }
}