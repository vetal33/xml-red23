<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class Parcel extends Model
{
    use HasFactory, PostgisTrait;
    protected $fillable = [
        'cad_nub',
        'usage',
        'geom',
        'area_origin',
        'user_id',
        'geom_id',
        'original_geom',
        'point'
    ];

    protected $postgisFields = [
        'geom',
        'point'
    ];

    protected $postgisTypes = [
        'point' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
    ];

/*    protected $casts = [
        'geom' => 'geometry',
    ];*/


    public function user(): ?User
    {
        return $this->belongsTo(User::class);
    }
}
