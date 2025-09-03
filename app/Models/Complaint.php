<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- TAMBAHKAN INI
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- TAMBAHKAN INI


class Complaint extends Model
{
    use HasFactory, SoftDeletes; // <-- TAMBAHKAN SoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'housing_unit_id',
        'photo_url',
        'latitude',
        'longitude',
        'status',
        'assignee_id',
        'due_date',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function complaintHistories(): HasMany
    {
        return $this->hasMany(ComplaintHistory::class);
    } //
    public function housingUnit(): BelongsTo
    {
        return $this->belongsTo(HousingUnit::class);
    }
}
