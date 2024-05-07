<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagesDuabelas extends Model
{
    use HasFactory;

    // DIS -----
    protected $guarded = ['updated_at'];
    public $timestamps = false;

    // KEY
    protected $primaryKey = 'kode_iklan';
    public $keyType = 'string';
    public $incrementing = false;
    // protected $hidden = ['email', 'kategori', 'kode_iklan'];
}
