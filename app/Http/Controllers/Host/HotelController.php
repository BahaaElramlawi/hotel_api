<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\HotelRequest;
use App\Models\Host;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Services\Host\HotelService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function show($id)
    {
        $response = $this->hotelService->getHotelByID($id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function store(HotelRequest $request)
    {
        $response = $this->hotelService->createHotel($request->validated());
        return response()->json($response, $response['status'] ? 201 : 500);
    }

    public function update(HotelRequest $request, $id)
    {
        $validatedData = $request->validated();
        $response = $this->hotelService->updateHotel($validatedData, $id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $response = $this->hotelService->deleteHotel($id);
        return response()->json($response, $response['status'] ? 200 : 500);
    }

}
