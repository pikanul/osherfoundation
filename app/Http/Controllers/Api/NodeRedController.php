<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NodeRedService;
use Illuminate\Http\Request;

class NodeRedController extends Controller
{
    protected function service(Request $request, NodeRedService $service): NodeRedService
    {
        $request->validate([
            'base_url' => 'required|url',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        return $service->setConnection(
            $request->base_url,
            $request->username,
            $request->password
        );
    }

    public function settings(Request $request, NodeRedService $service)
    {
        return response()->json($this->service($request, $service)->settings());
    }

    public function runtimeState(Request $request, NodeRedService $service)
    {
        return response()->json($this->service($request, $service)->runtimeState());
    }

    public function startRuntime(Request $request, NodeRedService $service)
    {
        return response()->json($this->service($request, $service)->startRuntime());
    }

    public function stopRuntime(Request $request, NodeRedService $service)
    {
        return response()->json($this->service($request, $service)->stopRuntime());
    }

    public function getFlows(Request $request, NodeRedService $service)
    {
        return response()->json($this->service($request, $service)->getFlows());
    }

    public function deployFlows(Request $request, NodeRedService $service)
    {
        $request->validate(['flows' => 'required|array', 'rev' => 'nullable|string']);

        return response()->json(
            $this->service($request, $service)->deployFlows($request->flows, $request->rev)
        );
    }
}
