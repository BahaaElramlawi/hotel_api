<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelRegion;
use Illuminate\Http\Request;

class HotelRegionController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $regions = HotelRegion::paginate($perPage);

            if ($regions->count() == 0) {
                $response = [
                    'status' => false,
                    'data' => null,
                    'message' => 'No regions found'
                ];
                return response($response, 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'regions' => $regions
                ],
                'message' => 'Regions retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to retrieve regions: ' . $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $region = HotelRegion::find($id);

            if (!$region) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Region not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'region' => $region
                ],
                'message' => 'Region retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to retrieve region: ' . $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'region_name' => 'required'
        ]);

        $region = new HotelRegion();
        $region->region_name = $validatedData['region_name'];

        if ($region->save()) {
            return response()->json([
                'status' => true,
                'data' => [
                    'region' => $region
                ],
                'message' => 'Region created successfully'
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to create region'
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $region = HotelRegion::find($id);

        if (!$region) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Region not found'
            ], 404);
        }

        $region->region_name = $request->input('region_name');
        $region->save();

        return response()->json([
            'status' => true,
            'data' => [
                'region' => $region
            ],
            'message' => 'Region updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $region = HotelRegion::find($id);

        if (!$region) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Region not found'
            ], 404);
        }

        $region->delete();

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Region deleted successfully'
        ], 200);
    }
}
