<?php

namespace App\Http\Controllers\Backend\Tutorials;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tutorail\TutorialRequest;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\TransshipmentCenters;
use App\Models\Tutorial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTutorialController extends Controller
{

    public function index()
    {


        GeneralLog('Eğitimler sayfası görüntülendi');

        return view('backend.tutorials.all_tutorials');
    }
    public function create()
    {
        $branch = getUserBranchInfo();

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Eğitim oluştur modülü görüntülendi.');
        return view('backend.tutorials.create', compact(['branch', 'agencies', 'tc']));
    }

    public function store(TutorialRequest $request)
    {
        $validated = $request->validated();

        parse_str(parse_url($validated['embedded_link'], PHP_URL_QUERY), $my_array_of_vars);

        $code =  $my_array_of_vars['v'];

        $validated['embedded_link'] = 'https://www.youtube.com/embed/' . $code . '?enablejsapi=1&version=3&playerapiid=ytplayer';

        GeneralLog('Eğitim oluşturuldu');

        Tutorial::create($validated);
        return [
            'status' => 1,
            'message' => 'Eğitim oluşturuldu!'
        ];
    }


    public function show($id)
    {
        $tutorial = Tutorial::find($id);

        return view('backend.tutorial.edit',['tutorial' => $tutorial]);
    }


    public function update(TutorialRequest $request, $id)
    {
        $tutorial = Tutorial::find($id);
        $validated = $request->validated();
        
        $tutorial->update($validated);

        return [
            'status' => 1,
            'message' => "Tamam",
        ];
    }


    public function destroy($id)
    {
        $tutorial = Tutorial::find($id);
        $delete = $tutorial->delete();
        if($delete) {
            return [
                'status' => 1,
                'message' => 'Eğitim silindi!'
            ];
        }
        
        return [
            'status' => 0,
            'message' => 'Eğitim silinemedi!'
        ];
        
    }

    public function edit($id)
    {
        $tutorial = Tutorial::find($id);

        return view('backend.tutorials.editTutorial',['tutorial' => $tutorial]);
    }
}
