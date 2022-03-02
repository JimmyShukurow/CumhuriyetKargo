<?php

namespace App\Http\Controllers\Backend\Safe;

use App\Actions\CKGSis\Safe\AgencySafe\DeletePaymentApp;
use App\Actions\CKGSis\Safe\AgencySafe\GetCardCollectionAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetMyPaymentsAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetPaymentAppAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetPendingCollectionsAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetCollectionsAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetSafeAction;
use App\Actions\CKGSis\Safe\AgencySafe\SafeAction;
use App\Http\Controllers\Controller;
use App\Models\AgencyPaymentApp;
use App\Models\CargoCollection;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\User;
use App\Rules\PriceControl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgencySafeController extends Controller
{

    function getDailySafeReport()
    {

        $data['agency_payments'] = DB::table('agency_payments')
            ->where('agency_id', Auth::user()->agency_code)
            ->sum('payment');

        $data['devreden_kasa'] = DB::table('cargoes')
            ->join('cargo_collections', 'cargo_collections.cargo_id', '=', 'cargoes.id')
            ->whereRaw('deleted_at is null')
            ->where('departure_agency_code', '=', Auth::user()->agency_code)
            ->where('cargoes.created_at', '<', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('collection_type_entered', 'NAKİT')
            ->sum('total_price');

        $data['devreden_kasa'] -= $data['agency_payments'];


        $data['gun_ici'] = DB::table('cargoes')
            ->join('cargo_collections', 'cargo_collections.cargo_id', '=', 'cargoes.id')
            ->whereRaw('deleted_at is null')
            ->where('departure_agency_code', '=', Auth::user()->agency_code)
            ->where('cargoes.created_at', '>', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('payment_type', '=', 'Gönderici Ödemeli')
            ->where('collection_type_entered', 'NAKİT')
            ->sum('total_price');

        $data['total'] = $data['devreden_kasa'] + $data['gun_ici'];
        $data['devreden_kasa'] = getDotter($data['devreden_kasa']);
        $data['gun_ici'] = getDotter($data['gun_ici']);
        $data['total'] = getDotter($data['total']);


        return $data;
    }

    public function index()
    {
        GeneralLog('Acente Kasası görüntülendi.');
        $data = $this->getDailySafeReport();

        return view('backend.safe.agency.index', compact('data'));
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

            case 'GetSafe':
                return GetSafeAction::run($request);
                break;

            case 'GetPaymentApps':
                return GetPaymentAppAction::run($request);
                break;

            case 'DeletePaymentApp':
                return DeletePaymentApp::run($request);
                break;

            case 'GetMyPayments':
                return GetMyPaymentsAction::run($request);
                break;

            case 'GetDailySafeReport':
                return response()->json(['status' => 1, 'data' => $this->getDailySafeReport()], 200);
                break;

            case 'GetCardCollections':
                return GetCardCollectionAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => 0, 'message' => 'no-case!'], 200);
                break;
        }
    }

    public function createPaymentApp()
    {
        $data['name'] = Auth::user()->name_surname . ' (' . Auth::user()->role->display_name . ')';
        $data['agency'] = '#' . Auth::user()->getAgency->agency_code . ' - ' . Auth::user()->getAgency->agency_name . ' ŞUBE';

        GeneralLog('Acente ödeme bildirgesi oluştur sayfası görüntülendi.');
        return view('backend.safe.agency.create_payment_app', compact('data'));
    }

    public function insertPaymentApp(Request $request)
    {
        $request->validate([
            'file1' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file2' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file3' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file4' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'paid' => ['required', new PriceControl],
        ]);

        global $file1, $file2, $file3, $file4;

        if ($request->hasFile('file1')) {
            $file1 = getJustFileName($request->file1->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file1->getClientOriginalExtension();
            $request->file1->move(public_path('files/app_files'), $file1);
        }
        if ($request->hasFile('file2')) {
            $file2 = getJustFileName($request->file2->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file2->getClientOriginalExtension();
            $request->file2->move(public_path('files/app_files'), $file2);
        }
        if ($request->hasFile('file3')) {
            $file3 = getJustFileName($request->file3->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file3->getClientOriginalExtension();
            $request->file3->move(public_path('files/app_files'), $file3);
        }

        $insert = AgencyPaymentApp::create([
            'agency_id' => Auth::user()->agency_code,
            'user_id' => Auth::id(),
            'paid' => getDoubleValue($request->paid),
            'file1' => $file1,
            'file2' => $file2,
            'file3' => $file3,
            'payment_channel' => 'EFT/HAVALE',
            'description' => $request->description,
            'currency' => 'TL',
        ]);

        if ($insert) {
            GeneralLog('Ödeme bildirgesi oluşturuldu!');
            return back()
                ->with('success', 'İşlem başarılı, ödeme bildirisi oluşturuldu. Onay bekliyor.');
        } else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz!');

    }
}
