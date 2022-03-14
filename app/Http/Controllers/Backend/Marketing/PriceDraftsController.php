<?php

namespace App\Http\Controllers\Backend\Marketing;

use App\Actions\CKGSis\Marketing\PriceDrafts\GetPriceDraftsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketing\PriceDraft\PriceDraftRequest;
use App\Models\FilePrice;
use App\Models\PriceDrafts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PriceDraftsController extends Controller
{
    public function index()
    {
        $tab = 'AdditionalServices';
        $filePrice = FilePrice::first();
        return view('backend.marketing.price_draft.index', compact(['tab', 'filePrice']));
    }

    public function create()
    {
        //
    }

    public function store(PriceDraftRequest $request)
    {
        $validated = $request->validated();

        try {
            $insert = PriceDrafts::create([
                'user_id' => Auth::id(),
                'name' => tr_strtoupper($validated['DraftName']),
                'file' => getDoubleValue($validated['FilePrice']),
                'mi' => getDoubleValue($validated['MiPrice']),
                'd_1_5' => getDoubleValue($validated['Desi1_5']),
                'd_6_10' => getDoubleValue($validated['Desi6_10']),
                'd_11_15' => getDoubleValue($validated['Desi11_15']),
                'd_16_20' => getDoubleValue($validated['Desi16_20']),
                'd_21_25' => getDoubleValue($validated['Desi21_25']),
                'd_26_30' => getDoubleValue($validated['Desi26_30']),
                'amount_of_increase' => getDoubleValue($validated['AmountOfIncrease']),
                'agency_permission' => $validated['AgencyPermission'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => -1, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }

        return response()->json(['status' => 1, 'message' => 'İşlem başarılı, Taslak oluşturuldu!'], 200);
    }

    public function GetPriceDrafts(Request $request)
    {
        return GetPriceDraftsAction::run($request);
    }

    public function show($id)
    {
        $draft = PriceDrafts::find($id);

        if ($draft != null)
            return response()->json(['status' => 1, 'data' => $draft], 200);
        else
            return response()->json(['status' => -1, 'message' => 'Fiyat taslağı bulunamadı!'], 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(PriceDraftRequest $request, $id)
    {
        $draft = PriceDrafts::find($id);

        if ($draft == null)
            return response()->json(['status' => -1, 'message' => 'Fiyat taslağı bulunamadı!'], 200);

        $validated = $request->validated();

        try {
            $insert = PriceDrafts::find($id)
                ->update([
                    'user_id' => Auth::id(),
                    'name' => tr_strtoupper($validated['DraftName']),
                    'file' => getDoubleValue($validated['FilePrice']),
                    'mi' => getDoubleValue($validated['MiPrice']),
                    'd_1_5' => getDoubleValue($validated['Desi1_5']),
                    'd_6_10' => getDoubleValue($validated['Desi6_10']),
                    'd_11_15' => getDoubleValue($validated['Desi11_15']),
                    'd_16_20' => getDoubleValue($validated['Desi16_20']),
                    'd_21_25' => getDoubleValue($validated['Desi21_25']),
                    'd_26_30' => getDoubleValue($validated['Desi26_30']),
                    'amount_of_increase' => getDoubleValue($validated['AmountOfIncrease']),
                    'agency_permission' => $validated['AgencyPermission'],
                ]);
        } catch (\Exception $e) {
            return response()->json(['status' => -1, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }

        return response()->json(['status' => 1, 'message' => 'İşlem başarılı, Taslak güncellendi!'], 200);
    }

    public function destroy($id)
    {
        $draft = PriceDrafts::find($id);

        if ($draft == null)
            return response()->json(['status' => -1, 'message' => 'Fiyat taslağı bulunamadı!'], 200);

        $delete = PriceDrafts::find($id)->delete();
        if ($delete)
            return 1;
        else
            return 0;
    }
}
