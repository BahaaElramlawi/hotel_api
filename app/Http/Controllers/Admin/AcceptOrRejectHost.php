<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Host;
use Illuminate\Http\Request;

class AcceptOrRejectHost extends Controller
{
    public function acceptOrReject(Request $request, $id)
    {
        try {
            $request->validate([
                'IsItAccepted' => 'required|boolean',
            ]);

            $host = Host::findOrFail($id);
            $host->IsItAccepted = $request->IsItAccepted;
            $host->save();

            $message = $host->IsItAccepted ? 'Host accepted successfully' : 'Host rejected successfully';

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $host,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update host status',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

}
