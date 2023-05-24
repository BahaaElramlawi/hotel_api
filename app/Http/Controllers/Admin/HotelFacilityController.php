<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HotelFacilityRequest;
use App\Models\HotelFacility;
use App\Services\Admin\HotelFacilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HotelFacilityController extends Controller
{
    protected $hotelFacilityService;

    public function __construct(HotelFacilityService $hotelFacilityService)
    {
        $this->hotelFacilityService = $hotelFacilityService;
    }

    public function index(Request $request)
    {
        $perPage = $request->has('per_page') ? (int)$request->per_page : 10;

        $response = $this->hotelFacilityService->getAllFacilities($perPage);

        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function show($id)
    {
        $response = $this->hotelFacilityService->getFacilityByID($id);

        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function store(HotelFacilityRequest $request)
    {
        $response = $this->hotelFacilityService->createFacility($request);
        return response()->json($response, $response['status'] ? 201 : 400);
    }

    public function update(HotelFacilityRequest $request, $id)
    {
        $response = $this->hotelFacilityService->updateFacility($request->all(), $id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $result = $this->hotelFacilityService->deleteFacility($id);

        return response()->json($result, $result['status'] ? 200 : 404);
    }

}
