<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcceptOrRejectHostRequest;
use App\Models\Host;
use App\Services\Admin\AcceptOrRejectHostService;
use Illuminate\Http\Request;

class AcceptOrRejectHost extends Controller
{
    private $hostService;

    public function __construct(AcceptOrRejectHostService $hostService)
    {
        $this->hostService = $hostService;
    }

    public function acceptOrReject(AcceptOrRejectHostRequest $request, $id)
    {
        $result = $this->hostService->acceptOrReject($request, $id);

        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
