<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HotelRegionRequest;
use App\Models\HotelRegion;
use App\Services\Admin\HotelRegionService;
use Illuminate\Http\Request;

class HotelRegionController extends Controller
{

    protected $hotelRegionService;

    public function __construct(HotelRegionService $hotelRegionService)
    {
        $this->hotelRegionService = $hotelRegionService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $response = $this->hotelRegionService->getAllRegions($perPage);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function show($id)
    {
        $result = $this->hotelRegionService->findRegionByID($id);
        $status = $result['status'] ? 200 : 404;
        return response()->json($result, $status);
    }

    public function store(HotelRegionRequest $request)
    {
        $response = $this->hotelRegionService->createRegion($request);
        return response()->json($response, $response['status'] ? 201 : 500);
    }

    public function update(HotelRegionRequest $request, $id)
    {
        $response = $this->hotelRegionService->updateRegion($request, $id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $response = $this->hotelRegionService->deleteRegion($id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }


}
