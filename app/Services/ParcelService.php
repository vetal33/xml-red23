<?php

namespace App\Services;

use App\Models\Parcel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use MStaack\LaravelPostgis\Geometries\Geometry;

class ParcelService
{
    private FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {

        $this->featureService = $featureService;
    }

    public function convertToJsonFrom63To4326(Collection $parcels): array
    {
        $parcelsToMap = [];
        $i = 0;

        foreach ($parcels as $parcel) {
            //$parcelsToMap[$i]['area'] = number_format(round(($parcel->getArea()) / 10000, 4), 4) . ' га';
            $parcelsToMap[$i]['cadNum'] = $parcel->cad_num;
            /** @var Geometry $geom */
            $geom = $parcel->geom;
            $wkt = $geom->toWKT();
            $parcelsToMap[$i]['wkt'] = $wkt;
            $parcelsToMap[$i]['usage'] = $parcel->usage;
            $parcelsToMap[$i]['extent'] = $this->featureService->getExtent($wkt);
            $parcelsToMap[$i]['id'] = $parcel->id;

            $coordJson = $this->featureService->getJsonFromWkt($wkt);
            $parcelsToMap[$i]['json'] = $coordJson;
            $i++;
        }

        return $parcelsToMap;
    }

    public function intersectParcels(string $parcelWktGeom, $idNewParcel = 0): array
    {
        $intrsectIds = [];
        $parcels = Parcel::myParcels()->get();

        if ($idNewParcel !== 0) {
            $filtered = $parcels->except(['id', $idNewParcel]);
        }

        foreach ($filtered as $parcel) {
            $geom = $parcel->original_geom;
            $wkt = $geom->toWKT();
            if ($this->featureService->isIntersect($parcelWktGeom, $wkt)) {
                $intrsectIds[] = $parcel->id;
            }
        }

        return $intrsectIds;
    }

    public function intersectParcelsAsGeom(Parcel $parcel)
    {
        try {
            foreach ($parcel->parcelIntersectProblems as $intersectProblem) {
                $intersectParcel = $intersectProblem->intersectParcel;
                $geom = $this->featureService->isIntersectAsGeom($parcel->original_geom->toWKT(), $intersectParcel->original_geom->toWKT());
                $intersectProblem->area = $this->featureService->calcArea($geom);
                $wktTransform = $this->featureService->transformFromSC63to4326($geom);
                $intersectProblem->geom_intersect = $wktTransform;
                $intersectProblem->save();
            }

            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }






    }

}
