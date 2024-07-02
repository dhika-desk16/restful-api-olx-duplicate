<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $primaryKey = 'favoritable_id';
    protected $guarded = ['updated_at'];
    public $timestamps = false;

    public function imagesDuabelas()
    {
        return $this->hasMany(ImagesDuabelas::class, 'kode_iklan', 'kode_iklan');
    }
    // public function favoritable()
    // {
    //     $prefix = substr($this->kode_iklan, 0, 3);

    //     switch ($prefix) {
    //         case 'MBL':
    //             return $this->belongsTo(IklanMobil::class, 'kode_iklan', 'kode_iklan');
    //         case 'MTR':
    //             return $this->belongsTo(IklanMotor::class, 'kode_iklan', 'kode_iklan');
    //         case 'PRT':
    //             return $this->belongsTo(IklanProperti::class, 'kode_iklan', 'kode_iklan');
    //         case 'HBO':
    //             return $this->belongsTo(IklanHobiDanOlahraga::class, 'kode_iklan', 'kode_iklan');
    //         case 'EDG':
    //             return $this->belongsTo(IklanElektronikDanGadget::class, 'kode_iklan', 'kode_iklan');
    //         case 'JLK':
    //             return $this->belongsTo(IklanJasaDanLowongan::class, 'kode_iklan', 'kode_iklan');
    //         case 'KDI':
    //             return $this->belongsTo(IklanKantorDanIndustri::class, 'kode_iklan', 'kode_iklan');
    //         case 'KPB':
    //             return $this->belongsTo(IklanKeperluanPribadi::class, 'kode_iklan', 'kode_iklan');
    //         case 'PBA':
    //             return $this->belongsTo(IklanPerlengkapanBayiDanAnak::class, 'kode_iklan', 'kode_iklan');
    //         case 'RMT':
    //             return $this->belongsTo(IklanRumahTangga::class, 'kode_iklan', 'kode_iklan');
    //         default:
    //             return null;
    //     }
    // }
}
