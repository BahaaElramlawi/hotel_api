<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HotelTagRequest;
use App\Models\Hotel;
use App\Models\HotelTag;
use App\Services\Admin\HotelTagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelTagController extends Controller
{
    protected $hotelService;

    public function __construct(HotelTagService $hotelTagService)
    {
        $this->hotelService = $hotelTagService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $hotels = $this->hotelService->getAllHotels($perPage);

        return response()->json($hotels);
    }

    public function show($id)
    {
        $hotelService = new HotelTagService();
        $result = $hotelService->getHotelTagByID($id);

        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function store(HotelTagRequest $request, HotelTagService $service)
    {
        $data = $request->validated();

        $response = $service->addHotelTag($data);

        return response()->json($response, $response['status'] ? 201 : 500);

    }

    public function update(HotelTagRequest $request, $id)
    {
        $response = $this->hotelService->updateHotelTag($request, $id);

        $status = $response['status'] ? 200 : 404;

        return response()->json($response, $status);
    }

    public function destroy($id)
    {
        $response = $this->hotelService->deleteHotelTag($id);

        return response()->json($response, $response['status'] ? 200 : 404);
    }
}
