<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\HotelLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelLocationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_name' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'region_id' => 'required|exists:hotel_regions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $location = HotelLocation::with('region')->create([
            'location_name' => $request->input('location_name'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
            'region_id' => $request->input('region_id'),
        ]);

        return response()->json([
            'status' => true,
            'data' => [
                'Location' => $location->load('region'),
            ],
            'message' => 'Location created successfully',
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $location = HotelLocation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'location_name' => 'string|max:255',
            'longitude' => 'numeric',
            'latitude' => 'numeric',
            'region_id' => 'exists:hotel_regions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $location->location_name = $request->input('location_name', $location->location_name);
        $location->longitude = $request->input('longitude', $location->longitude);
        $location->latitude = $request->input('latitude', $location->latitude);
        $location->region_id = $request->input('region_id', $location->region_id);

        $location->save();

        return response()->json([
            'status' => true,
            'data' => [
                'Location' => $location->load('region'),
            ],
            'message' => 'Location updated successfully',
        ], 200);
    }


    public function destroy($id)
    {
        $location = HotelLocation::find($id);
        if (!$location) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Location not found'
            ], 404);
        }

        $location->delete();

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Location deleted successfully'
        ]);
    }
}
