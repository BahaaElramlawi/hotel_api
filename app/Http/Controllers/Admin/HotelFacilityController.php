<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HotelFacilityController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->has('per_page') ? (int)$request->per_page : 10;

        $facilities = HotelFacility::paginate($perPage);

        if ($facilities->isEmpty()) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'No facilities found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'facilities' => $facilities
            ],
            'message' => 'Facilities retrieved successfully'
        ], 200);
    }

    public function show($id)
    {
        $facility = HotelFacility::find($id);

        if (!$facility) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Facility not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'facility' => $facility
            ],
            'message' => 'Facility retrieved successfully'
        ], 200);
    }

    // Function to store an image
    private function storeImage($request, $fileNamePrefix)
    {
        $image = $request->file('image');
        $fileName = $fileNamePrefix . time() . '.' . $image->getClientOriginalExtension();
        $originalName = $image->getClientOriginalName();
        $filePath = 'public/images/' . $fileName;
        Storage::put($filePath, file_get_contents($image));
        return $fileName . '.' . pathinfo($originalName, PATHINFO_EXTENSION);
    }

    // Function to create a new hotel facility
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $icon = $request->file('icon');

        if ($icon) {
            // Generate a unique file name
            $name = time() + rand(1, 1000000) . '.' . $icon->getClientOriginalExtension();
            // Store the uploaded image in the public disk
            $path = $icon->storeAs('public/images', $name);
        }

        $facility = new HotelFacility();
        $facility->name = $request->input('name');
        $facility->icon = $name ?? null;
        $facility->save();

        return response()->json([
            'status' => true,
            'data' => [
                'facility' => $facility
            ],
            'message' => 'Facility created successfully'
        ], 201);
    }

    // Function to update an existing hotel facility
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'file',
        ]);

        $facility = HotelFacility::find($id);

        if (!$facility) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Facility not found'
            ], 404);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $validator->errors()->first()
            ], 400);
        }

        if ($request->has('name')) {
            $facility->name = $request->input('name');
        }

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');

            if ($icon) {
                // Generate a unique file name
                $name = time() + rand(1, 1000000) . '.' . $icon->getClientOriginalExtension();
                // Store the uploaded image in the public disk
                $path = $icon->storeAs('public/images', $name);

                $facility->icon = $name;
            }
        }

        $facility->save();

        return response()->json([
            'status' => true,
            'data' => [
                'facility' => $facility
            ],
            'message' => 'Facility updated successfully'
        ], 200);
    }


    public function destroy($id)
    {
        // Get the HotelFacility object to delete
        $facility = HotelFacility::find($id);

        if (!$facility) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Facility not found'
            ], 404);
        }

        $facility->delete();

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Facility deleted successfully'
        ], 200);
    }


}
