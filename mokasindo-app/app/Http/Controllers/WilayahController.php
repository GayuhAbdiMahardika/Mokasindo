<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');

        $cities = City::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function districts(Request $request)
    {
        $cityId = $request->query('city_id');

        $districts = District::where('city_id', $cityId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    public function subDistricts(Request $request)
    {
        $districtId = $request->query('district_id');

        $subDistricts = SubDistrict::where('district_id', $districtId)
            ->orderBy('name')
            ->get(['id', 'name', 'postal_code']);  // <- penting: include postal_code

        return response()->json($subDistricts);
    }
}
