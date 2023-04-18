<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Illuminate\Http\Request;

class HostManagementController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $hosts = Host::paginate($perPage);

        if ($hosts->count() == 0) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'No hosts found'
            ];
            return response($response, 404);
        }

        $response = [
            'status' => true,
            'data' => $hosts,
            'message' => 'Hosts retrieved successfully'
        ];
        return response($response, 200);
    }


    public function show($id)
    {
        $host = Host::find($id);

        if (!$host) {
            $response = [
                'status' => false,
                'data' => null,
                'message' => 'Host not found'
            ];
            return response($response, 404);
        }

        $response = [
            'status' => true,
            'data' => $host,
            'message' => 'Host retrieved successfully'
        ];
        return response($response, 200);
    }

    public function destroy($id)
    {
        $host = Host::find($id);

        if (!$host) {
            $response = [
                'status' => false,
                'message' => 'Host not found'
            ];
            return response($response, 404);
        }

        $host->delete();

        $response = [
            'status' => true,
            'message' => 'Host deleted successfully'
        ];
        return response($response, 200);
    }

}
