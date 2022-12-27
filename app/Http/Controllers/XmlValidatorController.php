<?php

namespace App\Http\Controllers;

use App\Http\Requests\XmlNormativeRequest;
use App\Models\Media;
use App\Services\XmlService;
use Hyperized\Xml\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class XmlValidatorController extends Controller
{
    private XmlService $xmlService;

    public function __construct(XmlService $xmlService)
    {
        $this->xmlService = $xmlService;
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

        if ($activeXml->count() > 0 ) {
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
        //dd($validationStructureErrors);
        if (!empty($validationStructureErrors)) {
            //dd($validationStructureErrors);
            //return back()->with(['validationErrors' => $validationStructureErrors]);
            return redirect()->route('xml-validator.index')->with(['validationErrors' => $validationStructureErrors]);
        }

        return redirect()->route('xml-validator.index')->with(['structure' => true]);
        dd('ss');

        return back()->withErrors(['uploadFile' => $this->xmlService::ERROR_DEFAULT]);


    }
}
