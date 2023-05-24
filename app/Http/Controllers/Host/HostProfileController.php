<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\HostProfileRequest;
use App\Models\Host;
use App\Services\Host\HostProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HostProfileController extends Controller
{
    protected $hostProfileService;

    public function __construct(HostProfileService $hostProfileService)
    {
        $this->hostProfileService = $hostProfileService;
    }

    public function index($id)
    {
        $response = $this->hostProfileService->getHostProfileByID($id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }

    public function update(HostProfileRequest $request, $id)
    {
        $response = $this->hostProfileService->updateHostProfile($request, $id);
        return response()->json($response, $response['status'] ? 200 : 404);
    }
}

