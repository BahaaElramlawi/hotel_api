<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\HostLocationRequest;
use App\Models\HotelLocation;
use App\Services\Host\HotelLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelLocationController extends Controller
{
    protected $hotelLocationService;

    public function __construct(HotelLocationService $hotelLocationService)
    {
        $this->hotelLocationService = $hotelLocationService;
    }

    public function store(HostLocationRequest $request)
    {
        $response = $this->hotelLocationService->createLocation($request);
        return response()->json($response, $response['status'] ? 201 : 500);
    }

    public function update(HostLocationRequest $request, $id)
    {
        $response = $this->hotelLocationService->updateLocation($request, $id);
        return response()->json($response, $response['status'] ? 200 : 400);
    }

    public function destroy($id)
    {
        $response = $this->hotelLocationService->deleteLocation($id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }
}
