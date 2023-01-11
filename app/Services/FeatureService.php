<?php

namespace App\Services;
use App\Models\Parcel;
use App\Services\GeometryTrait;
use Illuminate\Support\Facades\DB;


class FeatureService
{
    use GeometryTrait;

    public function transformFromSK63To3857($wkt)
    {
        $zone = $this->getZoneFromCoords($wkt);
        $result = DB::select(DB::raw("select ST_AsText(st_transform(st_transform(ST_GeomFromText(:polygon, :zone), 4284), 3857))"), ['polygon' => $wkt, 'zone' => $zone]);
        $numberArr = (array)$result[0];

        return $numberArr['st_astext'];
    }

    public function transformFromSC63to4326(string $feature, int $zone = 0)
    {
        $zone = ($zone === 0) ? $this->getZoneFromCoords($feature) : $zone;
        $result = DB::select(DB::raw("select ST_AsText(st_transform(st_transform(ST_GeomFromText(:polygon, :zone), 4284), 4326))"), ['polygon' => $feature, 'zone' => $zone]);
        $numberArr = (array)$result[0];

        return $numberArr['st_astext'];
    }





}
