<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelTagController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $hotels = Hotel::with(['images', 'locations', 'tags', 'facilities'])
                ->paginate($perPage);

            $hotelData = [];
            foreach ($hotels as $hotel) {
                $images = $hotel->images()->pluck('image_url')->toArray();
                $location = $hotel->location_id;
                $tags = $hotel->tags()->pluck('name')->toArray();
                $facilities = $hotel->facilities()->pluck('name')->toArray();

                $hotelData[] = [
                    'id' => $hotel->id,
                    'host_id' => $hotel->host_id,
                    'name' => $hotel->name,
                    'price' => $hotel->price,
                    'rate' => $hotel->rate,
                    'description' => $hotel->description,
                    'locations' => $location,
                    'images' => $images,
                    'tags' => $tags,
                    'facilities' => $facilities,
                ];
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'hotels' => $hotelData,
                    'pagination' => [
                        'current_page' => $hotels->currentPage(),
                        'last_page' => $hotels->lastPage(),
                        'per_page' => $hotels->perPage(),
                        'total' => $hotels->total(),
                    ],
                ],
                'message' => 'Hotels retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to retrieve hotels: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $tag = HotelTag::findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => [
                    'tag' => $tag
                ],
                'message' => 'Tag retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to retrieve tag: ' . $e->getMessage()
            ], 404);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $tag = new HotelTag();
        $tag->name = $request->input('name');
        $tag->save();

        return response()->json([
            'status' => true,
            'data' => [
                'tag' => $tag
            ],
            'message' => 'Tag created successfully'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $tag = HotelTag::find($id);

        if (!$tag) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->name = $request->input('name');
        $tag->save();

        return response()->json([
            'status' => true,
            'data' => [
                'tag' => $tag
            ],
            'message' => 'Tag updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $tag = HotelTag::find($id);

        if (!$tag) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'status' => false,
            'data' => null,
            'message' => 'Tag deleted successfully'
        ], 200);
    }
}
