<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Redirect;

class UpdatePasswordException extends Exception
{
    public function render()
    {
        return redirect()->back();
    }
}
