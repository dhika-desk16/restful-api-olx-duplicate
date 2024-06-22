<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IklanProperti extends Model
{
    use HasFactory;
   // DIS -----
   protected $guarded = ['updated_at'];
   public $timestamps = false;
   
   // KEY
   protected $primaryKey = 'kode_iklan';
   public $keyType = 'string';
   public $incrementing = false;

   // RELATION
   public function imagesDuabelas()
   {
       return $this->hasMany(ImagesDuabelas::class, 'kode_iklan', 'kode_iklan');
   }

   public function favorites()
   {
       return $this->hasMany(Favorite::class, 'kode_iklan', 'kode_iklan');
   }
 
}
