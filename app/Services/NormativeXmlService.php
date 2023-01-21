<?php

namespace App\Services;

use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NormativeXmlService
{
    const PATH_DIR_NAME = 'xml-normative-file';
    const ERROR_DEFAULT = 'Помилка завантаження, спробуйте завантажить файл повторно';
    const ERROR_NUMBER = 0.00001;

    const ERROR_TYPE_AREA = 'error_type_area';

    const ERROR_TYPES = [
        self::ERROR_TYPE_AREA => 'Помилка площі',
    ];

    private array $errors = [];

    public $pathXsdSchema;
    private FeatureService $featureService;

    public function __construct(FeatureService $featureService)
    {
        //$this->pathXsdSchema = Storage::disk('local')->path('simple.xsd');
        $this->pathXsdSchema = Storage::disk('local')->path('2_5463337664826573801.xsd');
        $this->featureService = $featureService;
    }


    /**
     * @param UploadedFile $file
     * @return string
     */
    public function getXmlFileName(UploadedFile $file): string
    {
        $oldName = explode('.', $file->getClientOriginalName());
        $oldName = str_replace(' ', '_', $oldName);
        $dateTime = Carbon::now()->format('Y-m-d');

        return implode('_', [$oldName[0], $dateTime]) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * @param string $destination
     * @return \$1|\SimpleXMLElement|null
     */
    public function getSimpleXML(string $destination)
    {

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($destination);
        //dd($xml);
        if (!$xml) {
            foreach (libxml_get_errors() as $error) {
                $this->errors[] = $error->message;
            }

            return null;
        }

        return $xml;
    }

    /**
     * @param \SimpleXMLElement $fileXml
     * @return void
     */
    public function validateStructure(\SimpleXMLElement $fileXml): void
    {
        $xml = new DOMDocument();
        $xml->loadXML($fileXml->asXML());

        /** Передає повноваження при обробці помилок користувачу       */
        $use_errors = libxml_use_internal_errors(true);

        $result = $xml->schemaValidate($this->pathXsdSchema);
        if ($result === false) {
            $this->errors = libxml_get_errors();
        }
        libxml_use_internal_errors($use_errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function checkAreaFeature(array $wktData, string $nameFeature)
    {
        $result = true;
        foreach ($wktData as $itemFeature) {
            $calcArea = round($this->featureService->calcArea($itemFeature['wkt']) / 10000, 4);

            if (!(abs($itemFeature['area'] - $calcArea) < self::ERROR_NUMBER)) {
                $name = [
                    'name' => $itemFeature['name'],
                    'area' => $itemFeature['area'],
                    'calcArea' => $calcArea
                ];
                $this->errors[$nameFeature][] = $name;

                $result = $this->errors;
            }
        }

        return $result;
    }

    public function prepareJsonToWkt(array $parseJson): array
    {
        $wktData = [];
        foreach ($parseJson as $layerName => $value) {
            if ($layerName === 'boundary') {
                $wktData[$layerName][0]['wkt'] = $this->featureService->getGeomFromJsonAsWkt($value['coordinates']);
            } else {
                foreach ($value as $indexLayer => $layerValue) {
                    $wktData[$layerName][$indexLayer]['wkt'] = $this->featureService->getGeomFromJsonAsWkt($layerValue['coordinates']);
                    $wktData[$layerName][$indexLayer]['name'] = array_key_exists('code', $layerValue) ? $layerValue['code'] : $layerValue['name'];
                    $wktData[$layerName][$indexLayer]['area'] = array_key_exists('area', $layerValue) ? $layerValue['area'] : '';
                }
            }
        }

        return $wktData;
    }

    public function prepareAreaErrors(array $errors, $errorType)
    {
        $data = [];
        foreach ($errors as $error) {
            $data[] = [
                'type' => NormativeXmlService::ERROR_TYPES[$errorType],
                'text' => 'Номер - ' . $error['name'] . '. Площа в документі - ' . $error['area'] . '. Порахована площа - ' . $error['calcArea'] . '.',
                'isCritical' => 'Критична',
            ];
        }

        return $data;
    }

}
