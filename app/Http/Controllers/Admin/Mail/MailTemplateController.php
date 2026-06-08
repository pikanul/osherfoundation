<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Controller;

class MailTemplateController extends Controller
{
    public function __call($method, $parameters)
    {
        abort(501, static::class . '::' . $method . ' is not implemented.');
    }
}

