<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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
        'point',
        'extent',
        'is_passed'
    ];

    protected $postgisFields = [
        'geom',
        'point',
        'original_geom',
    ];

    protected $postgisTypes = [
        'point' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ],
    ];

    protected $casts = [
        'is_passed' => 'boolean',
    ];


    public function user(): ?User
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMyParcels($query)
    {
        $query
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'DESC');
    }

    public function parcelProblems()
    {
        return $this->hasMany(ParcelProblem::class);
    }
}
