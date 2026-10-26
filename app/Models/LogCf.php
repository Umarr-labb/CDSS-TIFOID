<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogCf extends Model
{
    protected $table = 'log_cf';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_konsultasi', 'id_rule', 'id_penyakit',
        'cf_pakar', 'cf_user_min', 'cf_evidence', 'cf_sebelum', 'cf_sesudah',
    ];
}