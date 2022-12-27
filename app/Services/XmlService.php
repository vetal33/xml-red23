<?php

namespace App\Services;

use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class XmlService
{
    const PATH_DIR_NAME = 'xml-normative-file';
    const ERROR_DEFAULT = 'Помилка завантаження, спробуйте завантажить файл повторно';

    private array $errors = [];
    public $pathXsdSchema;

    public function __construct()
    {
        //$this->pathXsdSchema = Storage::disk('local')->path('simple.xsd');
        $this->pathXsdSchema = Storage::disk('local')->path('2_5463337664826573801.xsd');
    }


    /**
     * @param UploadedFile $file
     * @return string
     */
    public function getXmlFileName(UploadedFile $file): string
    {
        $oldName = explode('.', $file->getClientOriginalName());
        $oldName = str_replace(' ', '_', $oldName );
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

}
