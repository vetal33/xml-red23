<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonUploadRequest;
use App\Services\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MapController extends Controller
{
    public function index()
    {
        return view('user.map.index');
    }

    public function uploadJson(Request $request, FeatureService $featureService)
    {
        if ($request->ajax()) {
            $rules = (new JsonUploadRequest())->rules();
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return response()->json(['result' => false, 'error' => $validator->errors()->first()]);
            }
            $uploadedFile = $request->file('jsonFile');
            $fileStr = file_get_contents($uploadedFile);

           // $jsonUploader->upload($uploadedFile);
            $wkt = $featureService->getGeomFromJsonAsWkt($fileStr);
            $data['area'] = $featureService->calcArea($wkt);

            //$transformFeature = $featureService->transformFromSK63To3857($wkt);
            $wktTransform = $featureService->transformFromSC63to4326($wkt);
            $data['wkt'] = $wkt;
            $jsonTransform = $featureService->getJsonFromWkt($wktTransform);
            $data['json'] = $jsonTransform;
            $data['result'] = true;

/*            Parcel::create([
                'user_id' => auth()->user()->id,
                'geom' => $wkt,
                'original_geom' => $wkt,
                'point' => new Point(37.422009, -122.084047),
            ]);*/

            return response()->json($data);

        }
    }
}
