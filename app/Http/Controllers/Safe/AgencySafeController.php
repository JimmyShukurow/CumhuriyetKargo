<?php

namespace App\Http\Controllers\Safe;

use App\Actions\CKGSis\Safe\AgencySafe\GetPendingCollectionsAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetCollectionsAction;
use App\Http\Controllers\Controller;
use App\Models\CargoCollection;
use App\Models\Cargoes;
use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgencySafeController extends Controller
{

    public function index()
    {

//        $cargo = DB::table('cargoes')->get();
//
//        foreach ($cargo as $key) {
//            echo 'id => ' . $key->id . '<br>';
//            echo 'creator_user_id => ' . $key->creator_user_id . '<br>';
//            echo 'creator_user_id => ' . $key->id . '<br>';
//            echo '<hr>';
//
//
//            $insert = DB::table('cargo_collections')
//                ->insert([
//                    'cargo_id' => $key->id,
//                    'collection_entered_user_id' => $key->creator_user_id,
//                    'collection_type_entered' => 'NAKİT',
//                    'created_at' => $key->created_at,
//                    'updated_at' => $key->updated_at,
//                ]);
//        }

        GeneralLog('Acente Kasası görüntülendi.');
        return view('backend.safe.agency.index');
    }

    public function ajaxTransactions(Request $request, $val)
    {

        switch ($val) {
            case 'GetCollections':
                return GetCollectionsAction::run($request);
                break;

            case 'GetPendingCollections':
                return GetPendingCollectionsAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => '0', 'message' => 'no-case!'], 200);
                break;
        }

    }
}
