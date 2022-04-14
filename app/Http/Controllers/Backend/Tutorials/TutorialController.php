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

class TutorialController extends Controller
{

    public function index()
    {
        $data['cities'] = Cities::all();
        $user = Auth::user();

        $unit = $user->branch;

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        $firstDate = Carbon::createFromDate(date('Y-m-d'))->addDay(-7)->format('Y-m-d');

        $tutorials = Tutorial::all();

        GeneralLog('Eğitim sayfası görüntülendi');
        return view('backend.tutorials.all_tutorials', compact(['data', 'unit', 'firstDate', 'agencies', 'tc', 'tutorials']));
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

        parse_str(parse_url($validated['embedded_link'], PHP_URL_QUERY), $my_array_of_vars);

        $code =  $my_array_of_vars['v'];

        $validated['embedded_link'] = 'https://www.youtube.com/embed/' . $code . '?enablejsapi=1&version=3&playerapiid=ytplayer';

        Tutorial::create($validated);
        return [
            'status' => 1,
            'message' => 'Eğitim oluşturuldu!'
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
        $tutorial = Tutorial::find($id);
        $tutorial->delete();

        return [
            'status' => 1,
            'message' => 'Eğitim silindi!'
        ];
    }
}
