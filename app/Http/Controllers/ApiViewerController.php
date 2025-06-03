<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiViewerController extends Controller
{
    public function index()
    {
        return view('api-viewer.index');
    }
}
