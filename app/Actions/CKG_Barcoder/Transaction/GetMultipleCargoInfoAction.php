<?php

namespace App\Actions\CKG_Barcoder\Transaction;

use App\Models\Cargoes;
use App\Models\CargoMovements;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMultipleCargoInfoAction
{
    use AsAction;

    public function handle($request)
    {

        $vals = explode('[TESLA]', $request->vals);

        $userId = Crypt::decryptString($vals[0]);
        $user = User::find($userId);
        if ($user == null)
            return response()->json(['status' => 0, 'message' => 'User not found!']);

        $idsString = substr($vals[1], 0, strlen($vals[1]) - 1);
        $idsArray = explode(',', $idsString);

        if ($idsString == null)
            return response()->json(['status' => 0, 'message' => 'Cargo array not found!']);

        $cargoes = [];
        $CargoPartCount = 0;

        foreach ($idsArray as $key) {

            $data['cargo'] = Cargoes::find($key);

            if ($data['cargo'] == null) {
                $cargoes[] = ['Cargo Not Found!'];
                continue;
            }

            $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);

            $data['cargo']->distance = getDotter($data['cargo']->distance);

            $data['sender'] = DB::table('currents')
                ->select(['current_code', 'tckn', 'category'])
                ->where('id', $data['cargo']->sender_id)
                ->first();

            $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

//            $data['movements'] = DB::table('cargo_movements')
//                ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
//                ->groupBy('group_id')
//                ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
//                ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
//                ->get();

            $data['movements'] = CargoMovements::with(['cargo', 'user.role'])
                ->where('cargo_id', $data['cargo']->id)
                ->orderBy('created_at')
                ->get();


            foreach ($data['movements'] as $key) {
                $format = Carbon::parse($key->created_at);
                $key->created_time = $format->format('Y-m-d H:m:s');
            }

            $data['receiver'] = DB::table('currents')
                ->select(['current_code', 'tckn', 'category'])
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
                ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result', 'created_at')
                ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
                ->get();

            $data['add_services'] = DB::table('cargo_add_services')
                ->select(['service_name', 'price'])
                ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                ->get();

            $data['part_details'] = DB::table('cargo_part_details')
                ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                ->get();

            $CargoPartCount += DB::table('cargo_part_details')
                ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                ->count();

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

            $cargoes[] = $data;
        }


        return response()
            ->json(['status' => 1, 'data' => $cargoes, 'total_count' => $CargoPartCount, 'cargo_count' => count($idsArray)], 200);
    }
}
