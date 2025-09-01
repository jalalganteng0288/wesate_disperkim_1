<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingUnit extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'developer_name',
        'contact_person',
        'unit_type',
        'total_units',
        'photo_gallery',
        'latitude',
        'longitude',
    ]; //
}
