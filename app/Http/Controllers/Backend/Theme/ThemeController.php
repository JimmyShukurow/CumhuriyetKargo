<?php

namespace App\Http\Controllers\Backend\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        return view('backend.theme.index');
    }
}
