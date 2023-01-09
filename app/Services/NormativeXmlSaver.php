<?php

namespace App\Services;

use App\Services\Interfaces\XmlSaverInterface;

class NormativeXmlSaver extends BaseXmlSaver implements XmlSaverInterface
{
    /**
     * @var string
     */
    private $shapePath;

    private $errors = [];

    /**
     * @var object|string
     */
    private $user;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var string
     */
    private $uniquePostfix;

    /** @var array */
    private $featureNormative = [];
    private FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }


    /**
     * Створюєм GeoJson фай для подальшого відображення на карті
     *
     * @param array $coord
     * @param bool $ifConvert
     * @return array
     */
    public function toGeoJson(array $coord, bool $ifConvert = true): array
    {
        $numberZone = $this->getNumberZoneFromCoord(reset($coord));

        $data = [];
        foreach ($coord as $key => $value) {
            if (!array_key_exists('external', $value)) {
                foreach ($value as $item => $valMulti) {
                    if (array_key_exists('internal', $valMulti['coordinates'])) {
                        $polygon = $this->arrayToPolygon($valMulti['coordinates']['external'], $valMulti['coordinates']['internal']);
                    } else {
                        $polygon = $this->arrayToPolygon($valMulti['coordinates']['external']);
                    }

                    $wkt = ($ifConvert) ? $this->convertToWGS($polygon, $numberZone) : $polygon->getWKT();
                    $number = $this->featureService->numberOfPoints($wkt);

                    $data[$key][$item]['coordinates'] = $this->getGeoJson($wkt);
                    $data[$key][$item]['points'] = $number;

                    if (array_key_exists('ZoneNumber', $valMulti)) {
                        $data[$key][$item]['name'] = $valMulti['ZoneNumber'];
                        $data[$key][$item]['km2'] = $valMulti['Km2'];
                    }
                    if (array_key_exists('LocalFactorCode', $valMulti)) {
                        $data[$key][$item]['name'] = $valMulti['NameFactor'];
                        $data[$key][$item]['code'] = $valMulti['LocalFactorCode'];
                    }
                    if (array_key_exists('CodeAgroGroup', $valMulti)) {
                        $data[$key][$item]['code'] = $valMulti['CodeAgroGroup'];
                    }
                    if (array_key_exists('RegionNumber', $valMulti)) {
                        $data[$key][$item]['name'] = $valMulti['RegionNumber'];
                        $data[$key][$item]['ki'] = $valMulti['RegionIndex'];
                    }
                }
            } else {
                $polygon = $this->arrayToPolygon($value['external']);
                $wkt = ($ifConvert) ? $this->convertToWGS($polygon, $numberZone) : $polygon->getWKT();
                $data[$key]['coordinates'] = $this->getGeoJson($wkt);
                $data[$key]['points'] = $this->featureService->numberOfPoints($wkt);

                foreach ($value as $k => $v) {
                    if ($k !== 'external') {
                        $data[$key][$k] = $v;
                    }
                }
            }
        }

        return $data;
    }
}
