<?php

namespace App\Http\Controllers;

use App\Http\Requests\XmlNormativeRequest;
use App\Models\Media;
use App\Services\NormativeXmlParser;
use App\Services\NormativeXmlSaver;
use App\Services\NormativeXmlService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class XmlValidatorController extends Controller
{
    private NormativeXmlService $xmlService;
    private NormativeXmlParser $normativeXmlParser;
    private NormativeXmlSaver $normativeXmlSaver;

    public function __construct(NormativeXmlService $xmlService, NormativeXmlParser $normativeXmlParser, NormativeXmlSaver $normativeXmlSaver)
    {
        $this->xmlService = $xmlService;
        $this->normativeXmlParser = $normativeXmlParser;
        $this->normativeXmlSaver = $normativeXmlSaver;
    }

    public function index()
    {
        $user = auth()->user();
        $xmlNormative = $user->xmlNormative->first();

        return view('user.xml-validator.index', compact('xmlNormative'));
    }

    public function store(XmlNormativeRequest $request)
    {
        $user = auth()->user();
        $file = $request->file('file');
        $fileName = $this->xmlService->getXmlFileName($file);
        $activeXml = $user->xmlNormative;
        $result = $file->storeAs($this->xmlService::PATH_DIR_NAME . '/' . $user->id, $fileName);

        if (!$result) {
            return redirect()->route('xml-validator.index')->withErrors('Помилка збереження файла');
        }

        if ($activeXml->count() > 0) {
            $activeFile = $activeXml->first();
            Storage::delete($activeFile->path);
            $activeFile->update([
                'name' => $file->getClientOriginalName(),
                'file_name' => $fileName,
                'type' => Media::TYPE_XML_NORMATIVE,
                'mime_type' => $file->getClientMimeType(),
                'path' => $result,
            ]);

            return redirect()->route('xml-validator.index');
        }

        Media::create([
            'user_id' => $user->id,
            'name' => $file->getClientOriginalName(),
            'file_name' => $fileName,
            'type' => Media::TYPE_XML_NORMATIVE,
            'mime_type' => $file->getClientMimeType(),
            'path' => $result,
        ]);

        return redirect()->route('xml-validator.index');
    }

    public function structureValidate(Media $file)
    {
        $xmlFile = $this->xmlService->getSimpleXML(Storage::disk('local')->path($file->path));
        if (!$xmlFile) {
            if (!empty($this->xmlService->getErrors())) {
                return back()->withErrors(['uploadFile' => $this->xmlService->getErrors()[0]]);
            }

            return back()->withErrors(['uploadFile' => $this->xmlService::ERROR_DEFAULT]);
        }

        $this->xmlService->validateStructure($xmlFile);
        $validationStructureErrors = $this->xmlService->getErrors();
        if (!empty($validationStructureErrors)) {
            return redirect()->route('xml-validator.index')->with(['validationErrors' => $validationStructureErrors]);
        }

        return redirect()->route('xml-validator.index')->with(['structure' => true]);
    }

    public function geomValidate(Request $request)
    {
        if ($request->ajax()) {
            $file = Media::getMediaById($request->file);
            if (!$file) {
                return response()->json(['result' => false, 'error' => 'Файл не знайдено']);
            }

            $xmlFile = $this->xmlService->getSimpleXML(Storage::disk('local')->path($file->path));
            if (!$xmlFile) {
                if (!empty($this->xmlService->getErrors())) {
                    return response()->json(['result' => false, 'error' => $this->xmlService->getErrors()[0]]);
                }

                return response()->json(['result' => false, 'error' => $this->xmlService::ERROR_DEFAULT]);
            }

            $parseXml = $this->normativeXmlParser->parse($xmlFile);
            if (!$parseXml) {
                /*return response()->json
                    ->withErrors(['uploadFile' => $this->normativeXmlParser->getErrors()[0]])
                    ->with(['structure' => true]);*/
            }

            $parseJson = $this->normativeXmlSaver->toGeoJson($parseXml, false);

            return response()->json(['result' => true, 'parseJson' => $parseJson]);
        }
    }

    public function geomZoneValidate(Request $request)
    {
        if ($request->ajax()) {
            $dataArray = json_decode($request->data, TRUE);
            $zones = ['zones' => $dataArray];
            $wktData = $this->xmlService->prepareJsonToWkt($zones);
            $result = $this->xmlService->checkAreaFeature($wktData['zones'], 'zones');
            if ($result !== true) {
                $nameBlock = 'Зони';
                $data = $this->xmlService->prepareAreaErrors($result['zones'], NormativeXmlService::ERROR_TYPE_AREA);
                $tableHtml = view('user.xml-validator.partials.normative-validation-errors', ['errors' => $data, 'nameBlock' => $nameBlock])->render();
                return response()->json(['result' => false, 'tableHtml' => $tableHtml]);
            }

            return response()->json(['result' => true]);
        }
    }

    public function geomRegionValidate(Request $request)
    {
        if ($request->ajax()) {
            $dataArray = json_decode($request->data, TRUE);
            $region = ['region' => $dataArray];
            $wktData = $this->xmlService->prepareJsonToWkt($region);
            $result = $this->xmlService->checkAreaFeature($wktData['region'], 'region');
            if ($result !== true) {
                $nameBlock = 'Райони';
                $data = $this->xmlService->prepareAreaErrors($result['region'], NormativeXmlService::ERROR_TYPE_AREA);
                $tableHtml = view('user.xml-validator.partials.normative-validation-errors', ['errors' => $data, 'nameBlock' => $nameBlock])->render();
                return response()->json(['result' => false, 'tableHtml' => $tableHtml]);
            }

            return response()->json(['result' => true]);
        }
    }


    public function printErrorsPdf(Media $file)
    {
        $xmlFile = $this->xmlService->getSimpleXML(Storage::disk('local')->path($file->path));
        $this->xmlService->validateStructure($xmlFile);
        $validationStructureErrors = $this->xmlService->getErrors();
        $pdf = PDF::loadView('user.xml-validator.errorsPdf', compact('validationStructureErrors', 'file'));

        return $pdf->setPaper('a4', 'portrait')->download('Errors_' . $file->name . date('_m_d_Y') . '_' . uniqid() . '.pdf');
    }
}
