<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Cargoes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCargoInfoAction
{
    use AsAction;

    public function handle($request)
    {
        if ($request->invoice_number != null)

            $data['cargo'] = Cargoes::where('invoice_number', $request->invoice_number)
                ->first();
        else if ($request->tracking_number != null)
            $data['cargo'] = Cargoes::where('tracking_no', str_replace(' ', '', $request->tracking_number))
                ->first();
        else
            $data['cargo'] = Cargoes::find($request->id);

        if ($data['cargo'] == null)
            return response()
                ->json(['status' => 0, 'message' => 'Kargo Bulunamadı!'], 200);

        $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);
        $data['cargo']->distance = getDotter($data['cargo']->distance);

        $data['cargo']->created_at = dateFormatForJsonOutput($data['cargo']->created_at);
        $data['cargo']->crypte_invoice_no = Crypt::encryptString($data['cargo']->invoice_number);

        $data['sender'] = DB::table('currents')
            ->select(['id', 'current_code', 'tckn', 'category'])
            ->where('id', $data['cargo']->sender_id)
            ->first();
        $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

        $data['movements'] = DB::table('cargo_movements')
            ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
            ->groupBy('group_id')
            ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
            ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
            ->where('importance', '=', 1)
            ->orderBy('created_at', 'asc')
            ->get();

        $data['movementsSecondary'] = DB::table('cargo_movements')
            ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
            ->groupBy('group_id')
            ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
            ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
            ->orderBy('created_at', 'asc')
            ->get();

        $data['receiver'] = DB::table('currents')
            ->select(['id', 'current_code', 'tckn', 'category'])
            ->where('id', $data['cargo']->receiver_id)
            ->first();

        $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

        $data['creator'] = DB::table('view_users_all_info')
            ->select(['name_surname', 'display_name'])
            ->where('id', $data['cargo']->creator_user_id)
            ->first();

        $data['departure'] = DB::table('agencies')
            ->select(['agency_code', 'agency_name', 'city', 'district'])
            ->where('id', $data['cargo']->departure_agency_code)
            ->first();

        $data['departure_tc'] = DB::table('transshipment_centers')
            ->select(['city', 'tc_name'])
            ->where('id', $data['cargo']->departure_tc_code)
            ->first();

        $data['arrival'] = DB::table('agencies')
            ->select(['agency_code', 'agency_name', 'city', 'district'])
            ->where('id', $data['cargo']->arrival_agency_code)
            ->first();

        $data['arrival_tc'] = DB::table('transshipment_centers')
            ->select(['city', 'tc_name'])
            ->where('id', $data['cargo']->arrival_tc_code)
            ->first();

        $data['sms'] = DB::table('sent_sms')
            ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result')
            ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['add_services'] = DB::table('cargo_add_services')
            ->select(['service_name', 'price'])
            ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['part_details'] = DB::table('cargo_part_details')
            ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $newPartDetais = [];
        foreach ($data['part_details'] as $key)
            $newPartDetais[] = [
                'cargo_id' => $key->cargo_id,
                'created_at' => $key->created_at,
                'cubic_meter_volume' => $key->cubic_meter_volume,
                'desi' => $key->desi,
                'height' => $key->height,
                'id' => $key->id,
                'part_no' => $key->part_no,
                'size' => $key->size,
                'tracking_no' => $key->tracking_no,
                'updated_at' => $key->updated_at,
                'weight' => $key->weight,
                'width' => $key->width,
                'barcode_no' => crypteTrackingNo(str_replace(' ', '', $data['cargo']->tracking_no) . ' ' . $key->part_no)
            ];

        $data['part_details'] = $newPartDetais;

        $data['cancellation_applications'] = DB::table('view_cargo_cancellation_app_detail')
            ->where('cargo_id', $data['cargo']->id)
            ->get();

        $data['official_reports'] = DB::table('view_official_reports_general_info')
            ->whereRaw("( cargo_invoice_number ='" . $data['cargo']->invoice_number . "' or  description like '%" . $data['cargo']->invoice_number . "%')")
            ->get();

        $data['status'] = 1;

        $data['bag_tracking_no'] = $data['cargo']->bagDetails->isNotEmpty() ? $data['cargo']->bagDetails()->first()->tracking_no : null;

        return response()
            ->json($data, 200);
    }
}
