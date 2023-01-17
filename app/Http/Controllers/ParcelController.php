<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\ParcelProblem;
use App\Services\FeatureService;
use App\Services\ParcelService;
use Illuminate\Http\Request;

class ParcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(int $parcelId)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Parcel $parcel, ParcelService $parcelService)
    {
        $parcelService->intersectParcelsAsGeom($parcel);

        return view('user.parcel.edit', compact('parcel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $parcel = Parcel::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$parcel) {
            return response()->json(['result' => false, 'error' => 'Ділянки не існує']);
        }

        $parcel->is_passed = true;
        $parcel->save();
        $parcels = Parcel::myParcels()->get();
        $tableHtml = view('user.map.partials.datatable', compact('parcels'))->render();

        return response()->json(['result' => true, 'tableHtml' => $tableHtml]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $parcelId)
    {
        $parcel = Parcel::where('id', $parcelId)->where('user_id', auth()->user()->id)->first();
        if (!$parcel) {
            return response()->json(['result' => false, 'error' => 'Ділянки не існує']);
        }

        foreach ($parcel->parcelProblems as $parcelProblem) {
            $parcelIn = ParcelProblem::where('parcel_id', $parcelProblem->parcel_intersect_id)
                ->where('parcel_intersect_id', $parcelProblem->parcel_id );
            if ($parcelIn) {
                $parcelIn->delete();
            }
            $parcelProblem->delete();
        }
        $parcel->delete();

        $parcels = Parcel::myParcels()->get();
        $tableHtml = view('user.map.partials.datatable', compact('parcels'))->render();

        return response()->json(['result' => true, 'tableHtml' => $tableHtml]);
    }
}
