<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Services\Admin\HotelManagementService;
use Illuminate\Http\Request;

class HotelManagementController extends Controller
{
    protected $hotelManagementService;

    public function __construct(HotelManagementService $hotelManagementService)
    {
        $this->hotelManagementService = $hotelManagementService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $response = $this->hotelManagementService->getAllHotels($perPage);
        return response()->json($response, $response['status'] ? 200 : 500);
    }

    public function show(Request $request, $id)
    {
        $response = $this->hotelManagementService->getHotelByID($id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $response = $this->hotelManagementService->deleteHotel($id);

        return response()->json($response, $response['status'] ? 200 : 500);
    }
}
