<?php

namespace App\Http\Controllers\Backend\Tutorials;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutorail\TutorialRequest;
use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{

    public function index()
    {
        //
    }
    public function create()
    {
        $branch = getUserBranchInfo();

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Sefer oluştur modülü görüntülendi.');
        return view('backend.tutorials.create', compact(['branch', 'agencies', 'tc']));
    }

    public function store(TutorialRequest $request)
    {
        $validated = $request->validated();
        Tutorial::create($validated);
        return [
            'status' => 1,
            'message' => 'Banzay'
        ];
    }


    public function show($id)
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
