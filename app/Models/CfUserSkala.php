<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CfUserSkala extends Model
{
    protected $table = 'cf_user_skala';
    protected $primaryKey = 'id_skala';
    public $timestamps = false;

    protected $fillable = ['label', 'nilai_cf'];
}