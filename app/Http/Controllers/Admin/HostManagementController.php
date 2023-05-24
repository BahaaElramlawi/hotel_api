<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\HostManagementService;
use Illuminate\Http\Request;

class HostManagementController extends Controller
{
    private $hostManagementService;

    public function __construct(HostManagementService $hostManagementService)
    {
        $this->hostManagementService = $hostManagementService;
    }

    public function index(Request $request, HostManagementService $hostManagementService)
    {
        $perPage = $request->input('per_page', 10);
        $response = $hostManagementService->getHosts($perPage);

        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function show($id)
    {
        $response = $this->hostManagementService->findHostByID($id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function destroy($id)
    {
        $response = $this->hostManagementService->deleteHost($id);

        return response()->json($response, $response['status'] ? 200 : 404);
    }

}
