<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Agencies;

class DebitController extends Controller
{

    public function debitTransaction($val = '', Request $request)
    {
        switch ($val) {

            case 'GetAgencyDebits':
                $agency = Agencies::find(Auth::user()->agency_code)
                    ->first();

                $debits = DB::select("select view_debit_details.*,
                (select count(*) from debits WHERE debits.ctn = view_debit_details.ctn and debits.deleted_at is null ) as debit_part_count
                 from view_debit_details
                 where view_debit_details.agency_code = $agency->id order by created_at desc");

                $data = ['debits' => $debits];
                break;

            default:
                $data = 'no-case';
        }

        return response()
            ->json($data, 200);

    }
}
