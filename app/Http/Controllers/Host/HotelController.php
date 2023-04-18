<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Host;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{

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

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'price' => 'required',
                'rate' => 'required',
                'description' => 'required',
                'images.*' => 'required|image|max:2048',
                'host_id' => 'required|integer|exists:hosts,id',
                'location_id' => 'required|integer|exists:hotel_locations,id',
                'tags.*' => 'required|integer|exists:hotel_tags,id', // validate the selected tags
                'facilities.*' => 'required|integer|exists:hotel_facilities,id', // validate the selected facilities
            ]);

            $host = Host::findOrFail($validatedData['host_id']);

            if (!$host->IsItAccepted) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'This host is not allowed to create new hotels.',
                ], 403); // Forbidden
            }

            $hotel = new Hotel();
            $hotel->host_id = $validatedData['host_id'];
            $hotel->name = $validatedData['name'];
            $hotel->price = $validatedData['price'];
            $hotel->rate = $validatedData['rate'];
            $hotel->description = $validatedData['description'];
            $hotel->location_id = $validatedData['location_id'];
            $hotel->save();

            // Attach the selected tags to the hotel
            $hotel->tags()->attach($validatedData['tags']);
            $hotel->facilities()->attach($validatedData['facilities']);

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $image) {
                    $imagePath = $this->uploadImage($image);
                    $imagePaths[] = $imagePath;

                    // Save the image path in the images table
                    $hotelImage = new HotelImage();
                    $hotelImage->image_url = $imagePath;
                    $hotelImage->hotel_id = $hotel->id;
                    $hotelImage->save();
                }
            }

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
                'message' => 'Hotel created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Hotel not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'rate' => 'required',
            'description' => 'required',
            'images.*' => 'nullable|image|max:2048',
            'location_id' => 'required|integer|exists:hotel_locations,id',
        ]);

        $hotel->name = $validatedData['name'];
        $hotel->price = $validatedData['price'];
        $hotel->rate = $validatedData['rate'];
        $hotel->description = $validatedData['description'];
        $hotel->location_id = $validatedData['location_id'];
        $hotel->save();

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $imagePath = $this->uploadImage($image);
                $imagePaths[] = $imagePath;

                // Save the image path in the images table
                $hotelImage = new HotelImage();
                $hotelImage->image_url = $imagePath;
                $hotelImage->hotel_id = $hotel->id;
                $hotelImage->save();
            }
        }

        return response()->json([
            'status' => true,
            'data' => [
                'hotel' => $hotel,
                'images' => $imagePaths,
            ],
            'message' => 'Hotel updated successfully'
        ], 201);

    }

    private function uploadImage(UploadedFile $image)
    {
        $path = 'public/images/';
        $name = time() + rand(1, 1000000) . '.' . $image->getClientOriginalName();
        Storage::put($path . $name, file_get_contents($image));

        return str_replace('public/', '', $name);
    }


    public function destroy($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
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
