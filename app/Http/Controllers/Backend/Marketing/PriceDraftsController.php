<?php

namespace App\Http\Controllers\Backend\Marketing;

use App\Http\Controllers\Controller;
use App\Models\FilePrice;
use Illuminate\Http\Request;

class PriceDraftsController extends Controller
{
    public function index()
    {
        $tab = 'AdditionalServices';
        $filePrice = FilePrice::first();
        return view('backend.service_fee.index', compact(['tab', 'filePrice']));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
