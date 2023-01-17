<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class ParcelProblem extends Model
{
    use HasFactory, PostgisTrait;

    protected $fillable = [
        'parcel_id',
        'type',
        'geom_id',
        'description',
        'parcel_intersect_id'
    ];

    protected $postgisFields = [
        'geom_intersect',
    ];

    const INTERSECTED = 'intersected';

    const TYPES_LABEL = [
        self::INTERSECTED => 'Перетин',
    ];


    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function intersectParcel()
    {
        return $this->belongsTo(Parcel::class, 'parcel_intersect_id','id');
    }

    //public function get
}
