<?php

namespace App\Http\Controllers;

use App\Http\Requests\JsonUploadRequest;
use App\Models\Parcel;
use App\Services\FeatureService;
use App\Services\ParcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MStaack\LaravelPostgis\Geometries\MultiPolygon;


class MapController extends Controller
{
    public function index()
    {
        $parcels = Parcel::myParcels()->get();

        return view('user.map.index', compact('parcels'));
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
            $extent = $featureService->getExtent($wktTransform);

            $data['wkt'] = $wkt;
            $jsonTransform = $featureService->getJsonFromWkt($wktTransform);
            $data['json'] = $jsonTransform;
            $data['result'] = true;

            $parcel = Parcel::create([
                'user_id' => auth()->user()->id,
                'extent' => $extent,
                'geom' => $wktTransform,
                'original_geom' => $wkt,
            ]);

            $data['id'] = $parcel->id;
            $parcels = Parcel::myParcels()->get();
            $tableHtml = view('user.map.partials.datatable', compact('parcels'))->render();

            return response()->json(['result' => true, 'tableHtml' => $tableHtml, 'parcel' => $data]);
        }
    }

    public function getAllParcels(Request $request, FeatureService $featureService, ParcelService $parcelService)
    {
        if ($request->ajax()) {
             $parcels = Parcel::myParcels()->get();
             $data = $parcelService->convertToJsonFrom63To4326($parcels);

            return response()->json($data);
        }
    }
}
