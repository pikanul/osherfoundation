<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function crudSuccess(string $title, bool $refresh = true, int $status = 200)
    {
        return response()->json([
            'title' => $title,
            'type' => 'success',
            'refresh' => $refresh ? 'true' : 'false',
        ], $status);
    }

    protected function crudError(string $title = 'Something went wrong!.', bool $refresh = false, int $status = 422)
    {
        return response()->json([
            'title' => $title,
            'type' => 'error',
            'refresh' => $refresh ? 'true' : 'false',
        ], $status);
    }
}
