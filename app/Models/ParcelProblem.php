<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelProblem extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_id',
        'type',
        'geom_id',
        'description',
    ];

    const INTERSECTED = 'intersected';


    public function parcel(): ?Parcel
    {
        return $this->belongsTo(Parcel::class);
    }
}
