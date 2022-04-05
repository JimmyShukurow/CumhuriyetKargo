<?php

namespace App\Actions\CKG_Barcoder\Transaction;

use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\CargoMovements;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCargoInfoAction
{
    use AsAction;

    public function handle($request)
    {

        if ($request->val == '')
            return response()->json(['status' => 0, 'message' => 'No Val!']);

        $vals = explode('[TESLA]', $request->val);


        $id = Crypt::decryptString($vals[0]);
        $invoiceNumber = Crypt::decryptString($vals[1]);

        $user = User::find($vals[0]);
        if ($user == null)
            return response()->json(['status' => 0, 'message' => 'User not found!']);

        $cargo = Cargoes::where('invoice_number', $invoiceNumber)->first();

        if ($cargo == null)
            return response()
                ->json(['status' => 0, 'message' => 'Kargo Bulunamdı!']);

        $data['cargo'] = Cargoes::find($cargo->id);

        $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);
        $data['cargo']->distance = getDotter($data['cargo']->distance);

        //$data['cargo']->created_at = dateFormatForJsonOutput($data['cargo']->created_at);
        $data['cargo']->crypte_invoice_no = Crypt::encryptString(Auth::id()) . "[TESLA]" . Crypt::encryptString($data['cargo']->invoice_number);

        $data['sender'] = DB::table('currents')
            ->select(['id', 'current_code', 'tckn', 'category'])
            ->where('id', $data['cargo']->sender_id)
            ->first();
        $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

        $data['movements'] = CargoMovements::with(['cargo', 'user.role'])
            ->where('cargo_id', $data['cargo']->id)
            ->orderBy('created_at')
            ->get();

        foreach ($data['movements'] as $key) {
            $format = Carbon::parse($key->created_at);
            $key->created_time = $format->format('Y-m-d H:m:s');
        }


        $data['receiver'] = DB::table('currents')
            ->select(['id', 'current_code', 'tckn', 'category'])
            ->where('id', $data['cargo']->receiver_id)
            ->first();

        $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

        $data['creator'] = User::with('role:id,display_name')
            ->where('id', $data['cargo']->creator_user_id)
            ->withTrashed()
            ->first();

        $data['creator'] = [
            'name_surname' => $data['creator']->name_surname,
            'display_name' => $data['creator']->role->display_name
        ];


        $data['departure'] = Agencies::where('id', $data['cargo']->departure_agency_code)
            ->select(['agency_code', 'agency_name', 'city', 'district'])
            ->withTrashed()
            ->first();

        $data['departure_tc'] = DB::table('transshipment_centers')
            ->select(['city', 'tc_name'])
            ->where('id', $data['cargo']->departure_tc_code)
            ->first();

        $data['arrival'] = Agencies::where('id', $data['cargo']->arrival_agency_code)
            ->select(['agency_code', 'agency_name', 'city', 'district'])
            ->withTrashed()
            ->first();

        $data['arrival_tc'] = DB::table('transshipment_centers')
            ->select(['city', 'tc_name'])
            ->where('id', $data['cargo']->arrival_tc_code)
            ->first();

        $data['sms'] = DB::table('sent_sms')
            ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result', 'created_at')
            ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['add_services'] = DB::table('cargo_add_services')
            ->select(['service_name', 'price'])
            ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
            ->get();

        $data['part_details'] = DB::table('cargo_part_details')
            ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
            ->orderBy('part_no')
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
            ->json(['status' => 1, 'data' => $data]);
    }
}
