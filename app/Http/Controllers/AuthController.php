<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class AuthController extends Controller
{
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
