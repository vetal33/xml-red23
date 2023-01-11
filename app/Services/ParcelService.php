<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
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

            $coordJson = $this->featureService->getJsonFromWkt($wkt);
            $parcelsToMap[$i]['json'] = $coordJson;
            $i++;
        }

        return $parcelsToMap;
    }

}
