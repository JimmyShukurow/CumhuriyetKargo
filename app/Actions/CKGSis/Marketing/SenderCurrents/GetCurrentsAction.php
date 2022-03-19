<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCurrentsAction
{
    use AsAction;

    public function handle($request)
    {
        $record = $request->record;
        $status = $request->status;
        $agency = $request->agency;
        $name = $request->name;
        $currentCode = str_replace([' ', '_'], '', $request->currentCode);
        $creatorUser = $request->creatorUser;
        $category = $request->category != -1 ? $request->category : '';
        $confirmed = $request->confirmed;

        $currents = DB::table('currents')
            ->join('agencies', 'currents.agency', '=', 'agencies.id')
            ->join('users', 'currents.created_by_user_id', '=', 'users.id')
            ->select(['currents.*', 'agencies.agency_name', 'users.name_surname'])
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($agency ? 'agency=' . $agency : '1 > 0')
            ->whereRaw($creatorUser ? 'created_by_user_id=' . $creatorUser : '1 > 0')
            ->whereRaw($status ? "currents.`status`='" . $status . "'" : '1 > 0')
            ->whereRaw($category ? "currents.`category`='" . $category . "'" : '1 > 0')
            ->whereRaw($request->filled('confirmed') ? "confirmed='" . $confirmed . "'" : '1 > 0')
            ->whereRaw($name ? "name like '%" . $name . "%'" : '1 > 0')
            ->whereRaw($record == '1' ? 'currents.deleted_at is null' : 'currents.deleted_at is not null')
            ->where('current_type', 'Gönderici');

        return datatables()->of($currents)
            ->editColumn('current_code', function ($current) {
                return CurrentCodeDesign($current->current_code);
            })
            ->editColumn('name', function ($current) {
                return Str::words($current->name, 3, '...');
            })
            ->editColumn('city', function ($current) {
                return $current->city . "/" . $current->district;
            })
            ->setRowId(function ($currents) {
                return "current-item-" . $currents->id;
            })
            ->editColumn('status', function ($currents) {
                return $currents->status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>';
            })
            ->editColumn('confirmed', function ($currents) {
                return $currents->confirmed == '1' ? '<b class="text-success">Onaylandı</b>' : '<b class="text-primary">Onay Bekliyor</b>';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->rawColumns(['edit', 'status', 'confirmed'])
            ->make(true);
    }
}
