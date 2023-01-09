<?php

namespace App\Services\Interfaces;

interface ParserXmlInterface
{
    public function parse(\SimpleXMLElement $simpleXMLElement);
}
