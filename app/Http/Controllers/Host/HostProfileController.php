<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HostProfileController extends Controller
{
    public function index($id)
    {
        $host = Host::find($id);
        if (!$host) {
            return response()->json([
                'status' => false,
                'message' => 'Host not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $host,
            'message' => 'Host profile retrieved successfully'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $host = Host::find($id);
        if (!$host) {
            return response()->json([
                'status' => false,
                'message' => 'Host not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'birthdate' => 'sometimes',
            'phone_number' => 'sometimes',
            'gender' => 'sometimes',
            'image' => 'nullable|file',
            'password' => 'nullable|string'
        ]);

        if (isset($validatedData['first_name'])) {
            $host->first_name = $validatedData['first_name'];
        }

        if (isset($validatedData['last_name'])) {
            $host->last_name = $validatedData['last_name'];
        }

        if (isset($validatedData['birthdate'])) {
            $host->birthdate = $validatedData['birthdate'];
        }

        if (isset($validatedData['phone_number'])) {
            $host->phone_number = $validatedData['phone_number'];
        }

        if (isset($validatedData['gender'])) {
            $host->gender = $validatedData['gender'];
        }

        if ($request->hasFile('image')) {
            $name = $this->uploadImage($request->file('image'));
            $host->image = $name;
        }

        if (isset($validatedData['password'])) {
            $host->password = bcrypt($validatedData['password']);
        }

        $host->save();

        return response()->json([
            'status' => true,
            'data' => $host,
            'message' => 'Host profile updated successfully'
        ], 200);
    }


//    public function update(Request $request, $id)
//    {
//        $host = Host::find($id);
//        if (!$host) {
//            return response()->json([
//                'status' => false,
//                'message' => 'Host not found',
//            ], 404);
//        }
//
//        $validatedData = $request->validate([
//            'first_name' => 'sometimes|required|string',
//            'last_name' => 'sometimes|required|string',
//            'birthdate' => 'sometimes|required|string',
//            'phone_number' => 'sometimes|required|string',
//            'gender' => 'sometimes|required|string',
//            'image' => 'nullable|file',
//            'password' => 'nullable|string'
//        ]);
//
//        $host->first_name = $validatedData['first_name'];
//        $host->last_name = $validatedData['last_name'];
//        $host->birthdate = $validatedData['birthdate'];
//        $host->phone_number = $validatedData['phone_number'];
//        $host->gender = $validatedData['gender'];
//
//        if ($request->hasFile('image')) {
//            $name = $this->uploadImage($request->file('image'));
//            $host->image = $name;
//        }
//
//        if ($validatedData['password']) {
//            $host->password = bcrypt($validatedData['password']);
//        }
//
//        $host->save();
//
//        return response()->json([
//            'status' => true,
//            'data' => $host,
//            'message' => 'Host profile updated successfully'
//        ], 200);
//    }

    private function uploadImage($image)
    {
        $name = time() + rand(1, 1000000) . '.' . $image->getClientOriginalName();
        $path = $image->storeAs('public/images', $name);
        return $name;
    }
}

