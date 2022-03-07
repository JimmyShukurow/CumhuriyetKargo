<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Actions\CKGSis\Dashboard\Gm\AjaxTransactions\GetRegionAnalysisAction;
use App\Actions\CKGSis\Dashboard\Gm\AjaxTransactions\GetSummeryAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Districts;
use App\Models\RegioanalDirectorates;
use App\Models\RegionalDistricts;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function gmDashboard()
    {
        GeneralLog('Dashboard (GM) görüntülendi.');
        return view('backend.dashboard.gm.index');
    }

    public function gmAjaxTransactions(Request $request, $val)
    {
        switch ($val) {
            case 'GetSummery':
                return GetSummeryAction::run($request);
                break;

            case 'GetRegionAnalysis':
                return GetRegionAnalysisAction::run($request);
                break;

            default:
                return response()->json(['status' => 0, 'message' => 'no-case'], 200);
                break;
        }
    }
}
