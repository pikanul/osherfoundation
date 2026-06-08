<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ValueTypeController extends Controller
{
    public function __call($method, $parameters)
    {
        abort(501, static::class . '::' . $method . ' is not implemented.');
    }
}

