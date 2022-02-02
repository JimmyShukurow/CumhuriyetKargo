<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Agencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMainDailySummeryAction
{
    use AsAction;

    public function handle()
    {
        $agency = Agencies::where('id', Auth::user()->agency_code)->first();

                ## daily report start
                $daily['package_count'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->whereNotIn('cargo_type', ['Dosya-Mi'])
                    ->where('departure_agency_code', $agency->id)
                    ->count();
                $daily['package_count'] = getDotter($daily['package_count']);

                $daily['file_count'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('cargo_type', 'Dosya-Mi')
                    ->where('departure_agency_code', $agency->id)
                    ->count();
                $daily['file_count'] = getDotter($daily['file_count']);

                $daily['total_cargo_count'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->count();
                $daily['total_cargo_count'] = getDotter($daily['total_cargo_count']);

                $daily['total_desi'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->sum('desi');
                $daily['total_desi'] = getDotter($daily['total_desi']);

                $daily['total_number_of_pieces'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->whereNotIn('cargo_type', ['Dosya', 'Mi'])
                    ->sum('number_of_pieces');
                $daily['total_number_of_pieces'] = getDotter($daily['total_number_of_pieces']);

                $daily['total_endorsement'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->sum('total_price');

                $daily['total_endorsement'] = getDotter(round($daily['total_endorsement'], 2));
                ## daily report end

                return response()
                    ->json($daily, 200);
    }
}
