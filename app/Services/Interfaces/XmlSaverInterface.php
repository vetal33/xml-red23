<?php

namespace App\Services\Interfaces;

interface XmlSaverInterface
{
    public function toGeoJson(array $data, bool $isConvert): array;
}
