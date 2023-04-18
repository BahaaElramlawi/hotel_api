<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelManagementController extends Controller
{
    public function index()
    {
        try {
            $hotels = Hotel::with(['images', 'locations', 'tags', 'facilities'])
                ->paginate(10);

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
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            $imagePaths = $hotel->images()->pluck('image_url')->toArray();
            $hotelData = $hotel->toArray();
            $hotelData['images'] = $imagePaths;
            $hotelData['Location'] = $hotel->location_id; // assuming the `Hotel` model has a `location` relationship
            $hotelData['tags'] = $hotel->tags()->pluck('name')->toArray(); // get the hotel's tags
            $hotelData['facilities'] = $hotel->facilities()->pluck('name')->toArray(); // get the hotel's facilities

            return response()->json([
                'status' => true,
                'data' => [
                    'hotel' => $hotelData,
                ],
                'message' => 'Hotel retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            $hotel->tags()->detach();
            $hotel->facilities()->detach();
            $hotel->images()->delete();
            $hotel->delete();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Hotel deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


}
