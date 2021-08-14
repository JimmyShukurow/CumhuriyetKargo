<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\TransshipmentCenters;
use App\Models\TrasferCars;
use App\Models\Various;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransferCarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['agencies'] = Various::all();
        $data['cities'] = Cities::all();
        GeneralLog('Various sayfası görüntülendi.');
        return view('backend.transfer_cars.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        GeneralLog('Acente oluştur sayfası görüntülendi.');
        return view('backend.transfer_cars.create', compact(['data']));
    }
    public function allData(){
        $transferCars = DB::table('trasfer_cars')
            ->select(['trasfer_cars.*']);


        return DataTables::of($transferCars)
            ->setRowId(function ($transferCars) {
                return 'car-item-' . $transferCars->id;
            })
            ->addColumn('edit', 'backend.transfer_cars.column')
            ->rawColumns(['edit'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        $data['myTransferCar']=TrasferCars::find($id);
        return view('backend.transfer_cars.edit', compact(['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $myData=TrasferCars::find($id);
        $stepne=$request->stepne == 'Evet' ? 1:0;
        $crick=$request->crick == 'Evet' ? 1:0;
        $chain=$request->chain == 'Evet' ? 1:0;
        $tireIron=$request->tireIron == 'Evet' ? 1:0;
        $reflektor=$request->reflektor == 'Evet' ? 1:0;
        $fireTube=$request->fireTube == 'Evet' ? 1:0;
        $firstAidKid=$request->firstAidKid == 'Evet' ? 1:0;
        $travelerLamp=$request->travelerLamp == 'Evet' ? 1:0;
        $towingline=$request->towingline == 'Evet' ? 1:0;

        $giydirmeKorNoktaUyarısı=$request->giydirmeKorNoktaUyarısı == 'Evet' ? 1:0;
        $hataBildirimHattı=$request->hataBildirimHattı == 'Evet' ? 1:0;
        $muayneEvrağı=$request->muayneEvrağı == 'Evet' ? 1:0;
        $sigortaBelgesi=$request->sigortaBelgesi == 'Evet' ? 1:0;
        $soforEhliyet=$request->soforEhliyet == 'Evet' ? 1:0;
        $srcBelgesi=$request->srcBelgesi == 'Evet' ? 1:0;
        $ruhsatEkpertizRaporu=$request->ruhsatEkpertizRaporu == 'Evet' ? 1:0;
        $tasimaBelgesi=$request->tasimaBelgesi == 'Evet' ? 1:0;
        $soferAdliSicilBelgesi=$request->soferAdliSicilBelgesi == 'Evet' ? 1:0;
        $aracSahibiSicilKaydi=$request->aracSahibiSicilKaydi == 'Evet' ? 1:0;
        $soferYakiniIkametgahBelgesi=$request->soferYakiniIkametgahBelgesi == 'Evet' ? 1:0;


        $stopTransfer = '';
        foreach ($request->stopTransfer  as $key => $value){
            $stopTransfer .= $value .',';
        }


        $myData->update([
            'arac_marka'=>$request->branchCars,
            'arac_model'=>$request->modelCars,
            'arac_yılı'=>$request->modelYear,
            'plaque'=>$request->plaqueCar,
            'arac_kapasitesi'=>$request->capacityCar,
            'tonnage'=>$request->tonnage,
            'desi'=>$request->desiCapacity,
            'ats'=>$request->atsInfo,
            'hat'=>$request->line,
            'cıkıs_aktarma'=>$request->exitTransfer,
            'ugradığı_aktarma'=>$stopTransfer,
            'driver_name'=>$request->driverName,
            'driver_phone'=>$request->driverPhone,
            'driver_adress'=>$request->driverAdress,
            'arac_sahibi_ad'=>$request->carOwner,
            'arac_sahibi_phone'=>$request->carOwnerPhone,
            'arac_sahibi_yakını_adı'=>$request->carOwnerRelative,
            'arac_sahibi_yakını_phone'=>$request->carOwnerRelativePhone,
            'arac_sahibi_adress'=>$request->carOwnerAdress,
            'arac_sahibi_yakını_adress'=>$request->carOwnerRelativeAdress,
            'aylık_kira_bedeli'=>$request->monthRentPrice,
            'kdv_haric_hakedis'=>$request->kdvHaricHakedis,
            'bir_sefer_kira_maliyeti'=>$request->oneRentPrice,
            'yakıt_oranı'=>$request->flueRate,
            'tur_km'=>$request->turKm,
            'sefer_km'=>$request->journeyKm,
            'bir_sefer_yakıt_maliyeti'=>$request->oneFlueJourneyPrice,
//            'sefer_maliyeti'=>
            'hakedis_plus_mazot1'=>$request->hakedisPlusMazot,
            'aylık_yakıt'=>$request->monthFlue,
            'stepne'=>$stepne,
            'kiriko'=>$crick,
            'zincir'=>$chain,
            'bijon_anahtarı'=>$tireIron,
            'reflektör'=>$reflektor,
            'yangın_tüpü'=>$fireTube,
            'ilk_yardım_çantası'=>$firstAidKid,
            'seyyar_lamba'=>$travelerLamp,
            'çekme_halatı'=>$towingline,
            'giydirme_kör_nokta_uarısı'=>$giydirmeKorNoktaUyarısı,
            'hata_bildirim_hattı'=>$hataBildirimHattı,
            'muayne_eğrağı'=>$muayneEvrağı,
            'sigorta_belgesi'=>$sigortaBelgesi,
            'src_belgesi'=>$srcBelgesi,
            'ruhsat_ekpertiz_raporu'=>$ruhsatEkpertizRaporu,
            'taşıma_belgesi'=>$tasimaBelgesi,
            'şoför_adli_sicil_kaydi'=>$soferAdliSicilBelgesi,
            'arac_sahibi_sicil_kaydı'=>$aracSahibiSicilKaydi,
            'şoför_yakını_ikametgah_belgesi'=>$soferYakiniIkametgahBelgesi,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
