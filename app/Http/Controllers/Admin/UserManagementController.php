<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $users = User::paginate($perPage);

        if ($users->count() == 0) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'No users found'
            ];
            return response($response, 404);
        }

        $response = [
            'status' => true,
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ];
        return response($response, 200);
    }



    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'User not found'
            ];
            return response($response, 404);
        }

        $response = [
            'status' => true,
            'data' => $user,
            'message' => 'User retrieved successfully'
        ];
        return response($response, 200);
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'status' => false,
                'data' => null,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response([
            'status' => true,
            'data' => null,
            'message' => 'User deleted successfully'
        ], 200);
    }


}
