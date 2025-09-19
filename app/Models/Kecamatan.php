<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_desa',
        'luas_wilayah_km2',
        'populasi',
        'total_rumah',
        'total_rutilahu',
        'status',
    ];

    /**
     * Relasi: Satu Kecamatan memiliki banyak data Perumahan (HousingUnit)
     */
    public function housingUnits()
    {
        return $this->hasMany(HousingUnit::class);
    }

    /**
     * Relasi: Satu Kecamatan memiliki banyak Pengaduan
     */
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}