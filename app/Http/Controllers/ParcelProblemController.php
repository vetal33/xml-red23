<?php

namespace App\Http\Controllers;

use App\Models\ParcelProblem;
use App\Services\FeatureService;

class ParcelProblemController extends Controller
{
    public function index()
    {

    }

    public function getIntersectGeom(int $parcelProblemId, FeatureService $featureService)
    {
        $parcelProblem = ParcelProblem::where('id', $parcelProblemId)->first();
        if (!$parcelProblem) {
            return response()->json(['result' => false, 'error' => 'Ділянки не існує']);
        }

        $wktTransform = $parcelProblem->geom_intersect->toWkt();
        $jsonTransform = $featureService->getJsonFromWkt($wktTransform);

        return response()->json(['result' => $this, 'json' => $jsonTransform]);

    }
}
