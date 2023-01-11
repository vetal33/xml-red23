<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

trait GeometryTrait
{
    public function numberOfPoints(string $geom): int
    {
        $places = DB::select(DB::raw("select ST_NPoints(ST_GeomFromText(:geom)) as number"), ['geom' => $geom]);
        $numberArr = (array)$places[0];

        return $numberArr['number'] - 1;
    }

    public function getGeomFromJsonAsWkt(string $file): string
    {
        $result = DB::select(DB::raw("SELECT ST_AsText(ST_GeomFromGeoJSON(:file)) AS wkt"), ['file' => $file]);
        $numberArr = (array)$result[0];

        return $numberArr['wkt'];
    }

    public function calcArea($geom)
    {
        $result = DB::select(DB::raw("select ST_Area(ST_GeomFromText(:geom)) as area"), ['geom' => $geom]);
        $numberArr = (array)$result[0];

        return $numberArr['area'];
    }

    /**
     * Повертає зону в залежності від координати
     *
     * @param string $wkt
     * @return int
     */
    private function getZoneFromCoords(string $wkt): int
    {
        $array = explode('(', $wkt);
        $firstDigitFromCoord = substr($this->getCoordByTypeFeature($array[0], $wkt), 0, 1);

        return (integer)('10630' . $firstDigitFromCoord);
    }

    /**
     * Повертає першу координату із WKT формату
     *
     * @param string $typeFeature
     * @param string $wkt
     * @return string
     */
    private function getCoordByTypeFeature(string $typeFeature, string $wkt): string
    {
        $coord = [];
        if ($typeFeature === 'MULTIPOLYGON') {
            $result = explode('(((', $wkt);
            $coord = explode(' ', $result[1]);
        } elseif ($typeFeature === 'POLYGON') {
            $result = explode('((', $wkt);
            $coord = explode(' ', $result[1]);
        }

        return $coord[0];
    }

    public function getJsonFromWkt(string $geom)
    {
        $result = DB::select(DB::raw("SELECT ST_AsGeoJSON(ST_GeomFromText(:geom)) AS json"), ['geom' => $geom]);
        $numberArr = (array)$result[0];

        return $numberArr['json'];
    }

    public function getExtent($geom)
    {
        $result = DB::select(DB::raw("select ST_Extent(ST_GeomFromText(:geom)) as extent"), ['geom' => $geom]);
        $numberArr = (array)$result[0];

        return $numberArr['extent'];
    }

    public function getJsonFromGeom($geom)
    {
        $result = DB::select(DB::raw("SELECT ST_AsGeoJSON(:geom) AS json"), ['geom' => $geom]);
        $numberArr = (array)$result[0];

        return $numberArr['json'];
    }

}
