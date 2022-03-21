<?php

namespace App\Http\Controllers\Backend\Expedition;

use App\Actions\CKGSis\Expedition\AjaxTransaction\GetExpeditionInfoAction;
use App\Actions\CKGSis\Expedition\AjaxTransaction\GetOutGoingExpeditionsAction;
use App\Actions\CKGSis\Expedition\ExpeditionMovementAction;
use App\Actions\CKGSis\Expedition\ExpeditionStoreAction;
use App\Actions\CKGSis\Layout\GetUserModuleAndSubModuleAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\Expedition;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpeditionController extends Controller
{
    public function create()
    {
        $branch = [];
        $branch = getUserBranchInfo();

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Sefer oluştur modülü görüntülendi.');
        return view('backend.expedition.create.create', compact(['branch', 'agencies', 'tc']));
    }

    public function store(Request $request)
    {
        return ExpeditionStoreAction::run($request);
    }

    public function outGoing($requestID = null)
    {
        $data['cities'] = Cities::all();
        $unit = '';

        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $unit = $agency->agency_name . ' ŞUBE';
        } else if (Auth::user()->user_type == 'Aktarma') {
            $agency = TransshipmentCenters::find(Auth::user()->tc_code);
            $unit = $agency->tc_name . ' TRM';
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        $firstDate = Carbon::createFromDate(date('Y-m-d'))->addDay(-7)->format('Y-m-d');

        GeneralLog('Giden seferler sayfası görüntülendi');
        return view('backend.expedition.outgoing.outgoing_expeditions', compact(['data', 'unit', 'firstDate', 'agencies', 'tc', 'requestID']));
    }

    public function ajaxTransactions(Request $request, $val)
    {
        switch ($val) {
            case 'GetOutGoingExpeditions':
                return GetOutGoingExpeditionsAction::run($request);
                break;
            case 'GetExpeditionInfo':
                return GetExpeditionInfoAction::run($request->id);
                break;

            default:
                return response()
                    ->json(['status' => 0, 'message' => 'no-case'], 200);

        }
    }

    public function delete(Request $request)
    {
        $expedition = Expedition::find($request->expedition_id);
        $expeditionCreator = User::find($expedition->user->id);
        $userDeleter = Auth::user();
        $agencyControl =
            $expeditionCreator->user_type == $userDeleter->user_type && ($expeditionCreator->agency_code == $userDeleter->agency_code ||
                $expeditionCreator->tc_code == $userDeleter->tc_code);
        if (!$agencyControl) {
            return response()->json([
                'status' => 0,
                'message' => 'Seferi Sadece Oluşturan Birim Silebilir!'
            ]);
        };

        if ($expedition->liveCargoes->count() != 0) {
            return response()->json([
                'status' => 0,
                'message' => 'Seferdeki kargolar işlem gördüğünden bu seferi silemezsiniz!'
            ]);
        }

        $expedition->delete();
        $description = $expedition->car->plaka . ' plakali aracin ' . $expedition->serial_no . ' seri numaralı seferi ' . $userDeleter->name_surname . '(' . $userDeleter->role->display_name . ')' . ' tarafından silinmiştir!';
        ExpeditionMovementAction::run($expedition->id, $userDeleter->id, $description);
        return response()->json([
            'status' => 1,
            'message' => 'Sefer Silindi!'
        ]);
    }

    public function show(Request $request, $id)
    {
        $expedition = GetExpeditionInfoAction::run($id);
        return view('backend.expedition.outgoing.outgoing_expeditions_modal', ['expedition' => $expedition]);
    }

    public function finish(Request $request)
    {
        $user = Auth::user();
        $expedition = Expedition::find($request->expedition_id);

        if ($expedition->done == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Bu sefer zaten bitirilmiş!',
            ]);
        }
        //Burda Cikish shube oldugu kontrol ediliyor
        if ($user->branch_details == $expedition->routes()->where('route_type', 1)->first()->branch) {
            $expedition->update(['done' => 1]);
            ExpeditionMovementAction::run($expedition->id, $user->id, 'Sefer bitirildi!.');
            return response()->json([
                'status' => 1,
                'message' => 'Sefer bitirildi',
            ]);

        }
        //Burda Varish shube oldugu kontrol ediliyor
        if ($user->branch_details == $expedition->routes()->where('route_type', -1)->first()->branch) {
            $expedition->update(['done' => 1]);
            ExpeditionMovementAction::run($expedition->id, $user->id, 'Sefer bitirildi!.');
            return response()->json([
                'status' => 1,
                'message' => 'Sefer bitirildi',
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Seferi Sadece Varış Birimi veya Çıkış Birimi Bitirebilir!',
        ]);
    }

}
