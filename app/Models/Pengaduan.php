<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'housing_unit_id', // <-- Pastikan ini ada
        'judul',
        'isi_laporan',
        'status',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Setiap pengaduan dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * === INI PERBAIKANNYA ===
     * Mendefinisikan relasi "belongsTo" ke model HousingUnit.
     * Setiap pengaduan bisa jadi dimiliki oleh satu perumahan.
     */
    public function housingUnit(): BelongsTo
    {
        return $this->belongsTo(HousingUnit::class);
    }
}