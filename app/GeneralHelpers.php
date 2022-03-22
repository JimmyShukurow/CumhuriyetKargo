<?php

use App\Models\CargoMovements;
use App\Models\Cities;
use App\Models\Currents;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tickets;
use App\Models\SentSms;
use App\Models\Debits;
use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Actions\CKGSis\Layout\GetUserModuleAndSubModuleAction;
use App\Models\User;

function tr_strtoupper($text)
{
    $search = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
    $replace = array("Ç", "İ", "I", "Ğ", "Ö", "Ş", "Ü");
    $text = str_replace($search, $replace, $text);
    $text = mb_strtoupper($text, 'utf-8');
    return $text;
}

function tr_strtolower($text)
{
    $search = array("Ç", "İ", "I", "Ğ", "Ö", "Ş", "Ü");
    $replace = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
    $text = str_replace($search, $replace, $text);
    $text = mb_strtolower($text, 'utf-8');
    return $text;
}

function enCharacters($text)
{
    $search = array("ç", "i", "ı", "ğ", "ö", "ş", "ü");
    $replace = array("c", "i", "i", "g", "o", "s", "u");
    $text = str_replace($search, $replace, $text);
    return $text;
}

function getDotter($TotalCiro)
{
    $TotalCiro = round($TotalCiro, 2);
    $virgul = "";
    if (strpos($TotalCiro, '.')) {
        $Virgulsuz = substr($TotalCiro, 0, strpos($TotalCiro, '.'));
        $virgul = substr($TotalCiro, strpos($TotalCiro, '.'), strlen($TotalCiro));
        $reverse = strrev($Virgulsuz);
    } else
        $reverse = strrev(substr($TotalCiro, 0, strlen($TotalCiro)));

    $newReverse = "";
    for ($i = 1; $i < strlen($reverse) + 1; $i++) {
        $newReverse .= $reverse[$i - 1];
        $i % 3 == 0 && $i != (strlen($reverse)) ? $newReverse .= ',' : '';
    }
    $TotalCiro = strrev($newReverse) . $virgul;

    return $TotalCiro;
}

function getWithK($count)
{
    $returner = $count / 1000;
    $returner = round($returner, 1);
    return $returner . 'B';
}

function urlCharacters($text)
{
    $search = array("undefined", "%C4%9F", "%C4%9E", "%20", "%C3%BC", "%C3%9C", "%C5%9F", "%C5%9E", "%C4%B0", "%C3%B6", "%C3%96", "%C3%A7", "%C3%87");
    $replace = array("", "ğ", "Ğ", " ", "ü", "Ü", "ş", "Ş", "i", "ö", "Ö", "ç", "Ç");
    $text = str_replace($search, $replace, $text);
    return $text;
}

function setActive($parameter, $name)
{
    return $parameter == $name ? 'active' : '';
}

function CharacterCleaner($text)
{
    $count = strlen($text);
    $new = "";
    for ($i = 0; $i < $count; $i++) {
        $new .= is_numeric($text[$i]) ? $text[$i] : '';
    }
    return intval($new);
}

function JustUsername($email)
{
    $index = strpos($email, '@');
    $email = substr($email, 0, $index);
    return $email;
}

function GeneralLog($log, $arr = '', $logName = 'General Motion')
{
    activity()
        ->withProperties($arr != '' ? $arr : null)
        ->inLog($logName)
        ->log($log);
}

function slash_counter($url)
{
    $counter = 0;
    for ($i = 0; $i < strlen($url); $i++) {
        if ($url[$i] == '/') $counter++;
    }
    return $counter;
}

function get_just_first_url()
{
    $url = \request()->url();
    $counter = slash_counter($url);

    if ($counter >= 4) {
        $url = substr($url, 10, strlen($url));
        $firstSlash = strpos($url, '/');
        $url = substr($url, $firstSlash + 1, strlen($url));
        $firstSlash = strpos($url, '/');
        $url = substr($url, 0, $firstSlash);
        return $url;
    } else {
        $url = substr($url, 10, strlen($url));
        $firstSlash = strpos($url, '/');
        $url = substr($url, $firstSlash + 1, strlen($url));
        return $url;
    }
}

function is_active($url, $className = 'active')
{
    if (\request()->is($url . '/*') || \request()->is($url)) return $className;
}

function set_module_active($module_id, $url, $className = 'active')
{
    $is_there = DB::table('view_module_sub_module_url')
        ->where([
            'module_id' => $module_id,
            'url_name' => $url
        ])
        ->count();

    if ($is_there > 0) {
        return $className;
    }
}

function UsersLogNames()
{
    $logNames = [
        'General Motion',
        'Password Reset',
        'Ticket Reply',
        'Login',
        'Cari Onayı'
    ];

    return $logNames;
}

function maxLogQuantity()
{
    $quantity = 1000;
    return $quantity;
}


function getLast10PersonelLog()
{
    $logs = DB::table('view_user_log_detail')
        ->select(['description', 'created_at'])
        ->where('causer_id', Auth::id())
        ->whereIn('log_name', UsersLogNames())
        ->limit(10)
        ->orderBy('id', 'desc')
        ->get();

    return $logs;
}

function getUserFirstPage()
{
    $modules = GetUserModuleAndSubModuleAction::run();
    return $modules[0]['sub_module_link'];
}

function Crypte4x($key)
{
    $key = Crypt::encryptString(Crypt::encryptString(Crypt::encryptString(Crypt::encryptString(Crypt::encryptString($key)))));
    return $key;
}

function Decrypte4x($key)
{
    $key = Crypt::decryptString(Crypt::decryptString(Crypt::decryptString(Crypt::decryptString(Crypt::decryptString($key)))));
    return $key;
}

function SendSMS($text, $number, $subject = 'UNKNOWN', $heading = 'CUMHURIYETK', $ctn = '')
{

    if (env('APP_DEBUG') == true) {
        return true;
    } else {

        $xmlContent = '<?xml version="1.0"?>
             <SMS>
                 <authentication>
                     <username>cumhuriyetkargo</username>
                     <password>Cumhuriyet1416</password>
                 </authentication>
                 <message>
                     <originator>CUMHURIYETK</originator>
                     <text>' . $text . '</text>
                     <unicode>8</unicode>
                     <international></international>
                 </message>
                 <receivers>
                     <receiver>' . $number . '</receiver>
                 </receivers>
             </SMS>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, "http://panel.kayseritoplusms.com/xmlapi/sendsms");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlContent);
        $xmlArrayAlici = simplexml_load_string(curl_exec($ch));

        $insert = SentSms::create([
            'company' => 'KAYSERİ TOPLU SMS',
            'heading' => $heading,
            'subject' => tr_strtoupper($subject),
            'sms_content' => $text,
            'phone' => $number,
            'length' => strlen($text),
            'quantity' => intval((strlen($text) / 155)) + 1,
            'causer_user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'ctn' => $ctn,
            'result' => $xmlArrayAlici->status == 'OK' ? '1' : '0',
        ]);

        if ($xmlArrayAlici->status == "OK") {
            return true;
        } else
            return false;
    }

}

function SendSMS2($text, $number, $subject = 'UNKNOWN', $heading = 'CUMHURIYETK', $ctn = '')
{
    $text = str_replace(" ", "%20", $text);
    $string = "http://panel.ankaratoplusms.com/api/sendsms?kulladi=cumhuriyetkargo&sifre=Cumhuriyet1416&mesaj=" . $text . "&baslik=CUMHURIYETK&alicilar=$number";
    $fgc = file_get_contents($string);
    $xmlArrayAlici = simplexml_load_string($fgc);

    $insert = SentSms::create([
        'company' => 'KAYSERİ TOPLU SMS',
        'heading' => $heading,
        'subject' => tr_strtoupper($subject),
        'sms_content' => $text,
        'phone' => $number,
        'length' => strlen($text),
        'quantity' => intval((strlen($text) / 155)) + 1,
        'causer_user_id' => Auth::id(),
        'ip_address' => request()->ip(),
        'ctn' => $ctn,
        'result' => $xmlArrayAlici->status == 'OK' ? '1' : '0',
    ]);

    if ($xmlArrayAlici->status == "OK") {
        return true;
    } else {
        return false;
    }
}

function RandomPassword()
{
    $password = "";
    $array = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'W', 'X', 'Q', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'Y', 'Z', 'W', 'X', 'Q', '!'];
    for ($i = 0; $i < 7; $i++) {
        $rand = rand(0, count($array) - 1);
        $password .= $array[$rand];
    }
    $password .= rand(10, 99);
    return $password;
}

function AgencyRoles()
{
    $roles = ['Acente Müdürü', 'Acente Bilgisayar Operatörü', 'Kurye'];
    return $roles;
}

function TCRoles()
{
    $roles = ['Aktarma Yöneticisi', 'Okutucu', 'Aktarma Bilgisayar Operatörü', 'Aktarma Müdür Yardımcısı'];
    return $roles;
}

function PriorityColor($priority)
{
    switch ($priority) {

        case 'Kritik':
            return 'danger';
            break;

        case 'Acil':
            return 'warning';
            break;

        case 'Çok Yüksek':
            return 'primary';
            break;

        case 'Yüksek':
            return 'alternate';
            break;

        case 'Normal':
            return 'dark';
            break;

        case 'Düşük':
            return 'dark ';
            break;

        default:
            return 'secondary';
            break;
    }

}


function isRedirectedMessage($msg)
{
    if (substr($msg, 0, 28) == '#### ==> Redirected <== ####')
        return substr($msg, 32, strlen($msg));
    else
        return null;
}


function isUpdatedStatusMessage($msg)
{
    if (substr($msg, 0, 32) == '#### ==> Status Updated <== ####')
        return substr($msg, 36, strlen($msg));
    else
        return null;
}

function updateTicketTime($TicketID)
{
    $model = Tickets::find($TicketID);
    $model->touch();
}

function virtualLoginPermIds()
{
    # 1 => admin
    # 39 => system_supporter
    return $array = [1, 39];
}


function CreateSecurityCode()
{
    $code = rand(111111, 999999);
    $code = 'N' . substr($code, 0, 3) . '-' . substr($code, 3, 3);
    return $code;
}

function getLocalEventName($EventName)
{
    switch ($EventName) {
        case 'created':
            $EventName = 'oluşturuldu';
            break;

        case 'updated':
            $EventName = 'güncellendi';
            break;

        case 'deleted':
            $EventName = 'silindi';
            break;
    }
    return $EventName;
}

function tcno_dogrula($bilgiler)
{
    $gonder = '<?xml version="1.0" encoding="utf-8"?>
	<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Body>
	<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
	<TCKimlikNo>' . $bilgiler["tcno"] . '</TCKimlikNo>
	<Ad>' . $bilgiler["isim"] . '</Ad>
	<Soyad>' . $bilgiler["soyisim"] . '</Soyad>
	<DogumYili>' . $bilgiler["dogumyili"] . '</DogumYili>
	</TCKimlikNoDogrula>
	</soap:Body>
	</soap:Envelope>';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $gonder);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'POST /Service/KPSPublic.asmx HTTP/1.1',
        'Host: tckimlik.nvi.gov.tr',
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
        'Content-Length: ' . strlen($gonder)
    ));
    $gelen = curl_exec($ch);
    curl_close($ch);

    return strip_tags($gelen);
}

function calcDistancePrice($distance)
{
    ## get distance price
    $dbDistance = Settings::where('key', 'distance')->first();
    $dbDistancePrice = Settings::where('key', 'distance_price')->first();

    $distanceUnitPrice = $dbDistancePrice->value / $dbDistance->value;
    $distancePrice = $distance * $distanceUnitPrice;

    return round($distancePrice, 2);
}

function calcDistance($start, $end)
{
    if ($start == $end)
        return 0;

    ## get plaque
    $startPoint = Cities::where('city_name', $start)->first('plaque');
    $endPoint = Cities::where('city_name', $end)->first('plaque');

    $json = json_decode(distances(), true);
    $distance = $json[$startPoint->plaque][$endPoint->plaque];

    return $distance;
}

function getDoubleValue($value)
{
    return $doubleVal = doubleval(str_replace(['₺', ',', '%'], '', $value));
}

function GetCurrentConfirmerRoleIDs()
{
//    [
//        1 => 'admin',
//        28 => 'marketing menager'
//    ]
    return [1, 28];
}

function getSystemVersion()
{
    $latestVersion = \App\Models\SystemUpdate::orderBy('id', 'desc')->first();

    if ($latestVersion === null)
        return '2.0.0.0';

    return $latestVersion;
}

function CatchDesiInterval($desi)
{
    if ($desi > 0 && $desi <= 5)
        return 'd_1_5';
    else if ($desi > 5 && $desi <= 10)
        return 'd_6_10';
    else if ($desi > 10 && $desi <= 15)
        return 'd_11_15';
    else if ($desi > 15 && $desi <= 20)
        return 'd_16_20';
    else if ($desi > 20 && $desi <= 25)
        return 'd_21_25';
    else if ($desi > 25 && $desi <= 30)
        return 'd_26_30';
    else if ($desi > 30 && $desi <= 35)
        return 'd_31_35';
    else if ($desi > 35 && $desi <= 40)
        return 'd_36_40';
    else if ($desi > 40 && $desi <= 45)
        return 'd_41_45';
    else if ($desi > 45 && $desi <= 50)
        return 'd_46_50';
}

function CurrentControl($currentCode)
{
    $array = [];
    $current = Currents::where('current_code', $currentCode)->first();

    if ($current->status != 1) {
        return $array = ['status' => -1, 'result' => $current->name . ' isimli müşteri aktif değil.'];
    } else if ($current->confirmed != 1) {
        return $array = ['status' => -1, 'result' => $current->name . ' isimli müşteri onaylanmamış.'];
    }

    if ($current->category == 'Anlaşmalı') {

        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));
        $startDate = date('Y-m-d', strtotime($current->contract_start_date));
        $endDate = date('Y-m-d', strtotime($current->contract_end_date));

        if (!($currentDate >= $startDate) && ($currentDate <= $endDate))
            return $array = ['status' => -1, 'result' => $current->name . ' isimli müşterinin sözleşme tarihi geçerli değil.'];

        $agencyOfCurrent = Agencies::where('id', $current->agency)->withTrashed()->first();

        if (Auth::user()->agency_code != $current->agency)
            return $array = ['status' => -1, 'result' => $current->name . ' müşterisinin bağlı olduğu şube ' . $agencyOfCurrent->agency_name . ' ŞUBE olduğundan, bu cari şubenizde kullanılamaz.'];

    }

    return $array = ['status' => 1];
}

function compareFloatEquality($a, $b)
{
    if ($a == 0 && $b == 0)
        return true;
    else if (abs(($a - $b) / $b) <= 0.00001)
        return true;
    else
        return false;
}

function AddressMaker($city, $district, $neighborhood, $street, $street2, $building_no, $floor, $door_no, $addressNote)
{
    $address = $city . '/' . $district . ' ' . $neighborhood . ' ';
    $address .= $street != '' ? $street . ' CD. ' : '';
    $address .= $street2 != '' ? $street2 . ' SK. ' : '';
    $address .= 'NO:' . $building_no . ' KAT:' . $floor . ' D:' . $door_no;
    $address .= $addressNote != '' ? ' (' . $addressNote . ')' : '';
    return $address;
}

function CreateCargoTrackingNo($agencyCode)
{

    $agency = \App\Models\Agencies::find($agencyCode);
    $city = Cities::where('city_name', $agency->city)->first();
    $ctn = $city->plaque . $agency->agency_code;

    $i = 0;
    while (true) {
        if ($i < 100)
            $rnd = rand(111111111111, 999999999999);
        else
            $rnd = rand(123456789101234, 999999999999999);

        $ctn .= substr($rnd, 0, (15 - strlen($ctn)));
        $cargo = DB::table('cargoes')
            ->where('tracking_no', $ctn)
            ->first();

        if ($cargo == null)
            break;

        $i++;
    }
    return $ctn;
}

function CreateCargoBagTrackingNo()
{

    if (Auth::user()->user_type == 'Acente') {

        $agency = Agencies::find(Auth::user()->agency_code);
        $city = Cities::where('city_name', $agency->city)->first();
        $ctn = $city->plaque . $agency->agency_code;

    } else if (Auth::user()->user_type == 'Aktarma') {

        $agency = TransshipmentCenters::find(Auth::user()->tc_code);
        $city = Cities::where('city_name', $agency->city)->first();
        $ctn = $city->plaque . $agency->id;

    }

    $i = 0;
    while (true) {
        if ($i < 100)
            $rnd = rand(111111111111, 999999999999);
        else
            $rnd = rand(123456789101234, 999999999999999);

        $ctn .= substr($rnd, 0, (15 - strlen($ctn)));
        $cargo = DB::table('cargo_bags')
            ->where('tracking_no', $ctn)
            ->first();

        if ($cargo == null)
            break;

        $i++;
    }
    return $ctn;
}


function TrackingNumberDesign($tracking_no)
{
    $new_no = substr($tracking_no, 0, 5) . ' ' . substr($tracking_no, 5, 5) . ' ' . substr($tracking_no, 10, 5);
    return $new_no;
}

function CurrentCodeDesign($current_code)
{
    $new_code = substr($current_code, 0, 3) . ' ' . substr($current_code, 3, 3) . ' ' . substr($current_code, 6, 3);
    return $new_code;
}

function GetSettingsVal($key)
{
    $val = DB::table('settings')
        ->where('key', $key)
        ->first();

    $val = $val->value;

    return $val;
}

function CargoTypes()
{
    # just here - no db
    $cargoTypes = [
        'Dosya-Mi',
        'Paket',
        'Koli',
        'Çuval',
        'Rulo',
        'Palet Kargo',
        'Sandık'
    ];
}

function getTCofAgency($agency_id)
{
    $agency = DB::table('agencies')
        ->where('id', $agency_id)
        ->first();

    $tcLocation = DB::table('transshipment_center_districts')
        ->where('city', $agency->city)
        ->where('district', $agency->district)
        ->first();

    $tc = DB::table('transshipment_centers')
        ->where('id', $tcLocation->tc_id)
        ->first();

    return $tc;
}

# this function for api
function FileUrlGenerator($file)
{
    $url = "https://www.nurullahguc.com/backend/assets/ticket_files/";
    if ($file == '')
        return $file;
    else
        return $file = $url . $file;

}

function InsertCargoMovement($ctn, $cargoID, $userID, $partNo, $info, $status, $group_id, $importance = 1)
{
    try {
        $insert = CargoMovements::create([
            'ctn' => $ctn,
            'cargo_id' => $cargoID,
            'user_id' => $userID,
            'part_no' => $partNo,
            'info' => $info,
            'status' => $status,
            'group_id' => $group_id,
            'importance' => $importance
        ]);

        return $insert;

    } catch (Exception $e) {
        return false;
    }

}

function InsertDebits($ctn, $cargoID, $partNo, $userID, $movementID)
{
    try {
        $agency = Agencies::find(Auth::user()->agency_code);

        Debits::create([
            'cargo_id' => $cargoID,
            'ctn' => $ctn,
            'part_no' => $partNo,
            'user_id' => $userID,
            'agency_code' => $agency->id,
            'movement_id' => $movementID,
        ]);

        return true;
    } catch (Exception $e) {
        return false;
    }

}

function crypteTrackingNo($number)
{
    $replacers = array(
        'J' => '0',
        'DY' => '1',
        'GU' => '2',
        '%' => '3',
        'OS' => '4',
        '&' => '5',
        'X' => '6',
        '$' => '7',
        'ZO' => '8',
        'F' => '9',
        'T#' => ' '
    );

    $val = "";
    for ($i = 0; $i < strlen($number); $i++)
        $val = $val . array_search($number[$i], $replacers);

    return $val;
}

function decryptTrackingNo($number = '')
{
    $replacers = array(
        '?' => 'J',
        '1' => 'DY',
        '2' => 'GU',
        '3' => '%',
        '4' => 'OS',
        '5' => '&',
        '6' => 'X',
        '7' => '$',
        '8' => 'ZO',
        '9' => 'F',
        ' ' => 'T#'
    );

    $text = $number;

    $bitch = str_split($text);
    $decryptedVal = "";

    for ($i = 0; $i < count($bitch); $i++) {

        $single = substr($text, $i, 1);
        $double = substr($text, $i, 2);

        if (array_search($single, $replacers))
            $decryptedVal .= array_search($single, $replacers);
        else if (array_search($double, $replacers))
            $decryptedVal .= array_search($double, $replacers);
    }

    $decryptedVal = str_replace('?', '0', $decryptedVal);
    return $decryptedVal;
}

function DesignInvoiceNumber()
{
    $letters = ['A', 'X', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'U', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'Y', 'X', 'Z'];
    $invoiceNumber = "";

    while (true) {

        $rnd = rand(0, count($letters) - 1);
        $rnd2 = rand(0, count($letters) - 1);

        $prefix = $letters[$rnd] . $letters[$rnd2];
        $realRandom = rand(123456, 987654);

        $invoiceNumber = $prefix . '-' . $realRandom;

        #control Number
        $cargo = DB::table('cargoes')
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if ($cargo != null)
            continue;
        else
            break;
    }

    return $invoiceNumber;
}


function dateFormatForJsonOutput($date)
{
    $date = Carbon::parse($date);
    $date = $date->addHour(3);

    return $date;
}

function allCargoTypes()
{
    $CargoTypes = ['Dosya', 'Mi', 'Paket', 'Koli', 'Çuval', 'Rulo', 'Palet', 'Sandık', 'Valiz'];
//    $CargoTypes = ['Dosya', 'Koli'];
    return $CargoTypes;
}

function getNameFirstLatter($name)
{
    $arrayName = explode(' ', $name);

    $firstLetters = "";
    foreach ($arrayName as $key) {
        $firstLetters .= mb_substr($key, 0, 1, "utf-8") . '.';
    }

    return $firstLetters;
}

function ReportedUnitTypes()
{
    # => For HTF Report Create Page
    return ['Çıkış Şube', 'Çıkış TRM.', 'Varış Şube', 'Varış TRM.', 'Diğer Şube', 'Diğer TRM.'];
}


function CreateAgencyCode()
{
    $code = 0;

    while (true) {
        $code = rand(1111, 8999);

        $control = DB::table('agencies')
            ->where('agency_code', $code)
            ->first();

        if ($control != null)
            continue;
        else
            break;
    }

    return $code;
}


function OfficialReportsPermissions()
{
//    $array = [
//        1 => 'Genel Yönetici',
//        20 => 'Acente Müdürü',
//        29 => 'Aktarma Müdürü',
//        34 => 'Operasyon Müdürü',
//        44 => 'Aktarma Müdür Yardımcısı'
//    ];

    $permissionArray = [1, 20, 29, 34, 44];
    return $permissionArray;
}

function ajaxValidation($request)
{
    $rules = [
        'allrules' => 'required',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails())
        return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

}


function createNgiShipmentWithAddress()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ws.yurticikargo.com/KOPSWebServices/NgiShipmentInterfaceServices?wsdl',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ngis="http://yurticikargo.com.tr/NgiShipmentInterfaceServices">
   <soapenv:Header />
   <soapenv:Body>
      <ngis:createNgiShipmentWithAddress>
         <wsUserName>GONDERGELSINKMP</wsUserName>
         <wsPassword>kniu17DSf7ubm384</wsPassword>
         <wsUserLanguage>TR</wsUserLanguage>
         <shipmentData>
            <ngiDocumentKey>TCL180430018076</ngiDocumentKey>
            <cargoType>2</cargoType>
            <totalCargoCount>1</totalCargoCount>
            <totalDesi>25</totalDesi>
            <totalWeight>0</totalWeight>
            <personGiver>TEST ZCOBAN</personGiver>
            <description>TEST Desc</description>
            <productCode>STA</productCode>
            <complementaryProductDataArray>
            </complementaryProductDataArray>
            <docCargoDataArray>
               <ngiCargoKey>TCL180430018076</ngiCargoKey>
               <cargoType>2</cargoType>
               <cargoDesi>25</cargoDesi>
               <cargoWeight>0</cargoWeight>
               <cargoCount>1</cargoCount>
            </docCargoDataArray>
            <specialFieldDataArray>
               <specialFieldName>54</specialFieldName>
               <specialFieldValue>TCSVK000238076</specialFieldValue>
            </specialFieldDataArray>
            <codData>
               <ttInvoiceAmount/>
               <dcSelectedCredit/>
            </codData>
         </shipmentData>
         <XSenderCustAddress>
            <senderCustName>TEST TEDARİKÇİ TİC.A.Ş.-3</senderCustName>
            <senderAddress>ESENKENT MAH. ENVERPAŞA CAD. NO:8 REGNUM SİTESİ ESENYURT İSTANBUL</senderAddress>
            <cityId>34</cityId>
            <townName>ESENYURT</townName>
            <senderPhone>08503390022</senderPhone>
         </XSenderCustAddress>
         <XConsigneeCustAddress>
            <consigneeCustName>TEST XYZ ABC ALICI ADI-8</consigneeCustName>
            <consigneeAddress>KEMALPAŞA MAH. ALİ ŞEFİK BEY SK. NO:14 MANOLYA APT. D:7 ECEABAT ÇANAKKALE</consigneeAddress>
            <cityId>17</cityId>
            <townName>ECEABAT</townName>
            <consigneeMobilePhone>5301000000</consigneeMobilePhone>
         </XConsigneeCustAddress>
         <payerCustData>
            <invCustId>929621246</invCustId>
         </payerCustData>
      </ngis:createNgiShipmentWithAddress>
   </soapenv:Body>
</soapenv:Envelope>',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/xml'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
    $xml = new SimpleXMLElement($response);
    $body = $xml->xpath('//envBody')[0];
    $array = json_decode(json_encode((array)$body), TRUE);

    return $array['ns1createNgiShipmentWithAddressResponse']['XShipmentDataResponse'];
}

function getPartnerDesiPrice($desi)
{
    $desi = round($desi);

    ## calc desi price
    $maxDesiInterval = DB::table('desi_lists')
        ->orderBy('finish_desi', 'desc')
        ->first();
    $maxDesiPrice = $maxDesiInterval->corporate_unit_price;
    $maxDesiInterval = $maxDesiInterval->finish_desi;

    $desiPrice = 0;
    if ($desi > $maxDesiInterval) {

        $desiPrice = $maxDesiPrice;

        $amountOfIncrease = DB::table('settings')->where('key', 'partner_desi_amount_of_increase')->first();
        $amountOfIncrease = $amountOfIncrease->value;

        for ($i = $maxDesiInterval; $i < $desi; $i++)
            $desiPrice += $amountOfIncrease;
    } else {
        #catch interval
        $desiPrice = DB::table('desi_lists')
            ->where('start_desi', '<=', $desi)
            ->where('finish_desi', '>=', $desi)
            ->first();
        $desiPrice = $desiPrice->corporate_unit_price;
    }

    return $desiPrice;
}

function getDesiPrice($desi)
{
    $desi = round($desi);

    ## calc desi price
    $maxDesiInterval = DB::table('desi_lists')
        ->orderBy('finish_desi', 'desc')
        ->first();
    $maxDesiPrice = $maxDesiInterval->individual_unit_price;
    $maxDesiInterval = $maxDesiInterval->finish_desi;

    $desiPrice = 0;
    if ($desi > $maxDesiInterval) {

        $desiPrice = $maxDesiPrice;

        $amountOfIncrease = DB::table('settings')->where('key', 'desi_amount_of_increase')->first();
        $amountOfIncrease = $amountOfIncrease->value;

        for ($i = $maxDesiInterval; $i < $desi; $i++)
            $desiPrice += $amountOfIncrease;
    } else {
        #catch interval
        $desiPrice = DB::table('desi_lists')
            ->where('start_desi', '<=', $desi)
            ->where('finish_desi', '>=', $desi)
            ->first();
        $desiPrice = $desiPrice->individual_unit_price;
    }

    return $desiPrice;
}

function getJustFileName($name)
{
    return $name = substr($name, 0, strpos($name, '.'));
}

function getUserBranchInfo()
{
    ## Get Branch Info

    if (Auth::user()->user_type == 'Acente') {
        $agency = Agencies::find(Auth::user()->agency_code);
        $branch = [
            'id' => $agency->id,
            'code' => $agency->agency_code,
            'city' => $agency->city,
            'name' => $agency->agency_name,
            'type' => 'ŞUBE',
            'type2' => 'Acente'
        ];
    } else {
        $tc = TransshipmentCenters::find(Auth::user()->tc_code);
        $branch = [
            'id' => $tc->id,
            'city' => $tc->city,
            'name' => $tc->tc_name,
            'type' => 'TRM.',
            'type2' => 'Aktarma',
        ];
    }

    return $branch;
}

function GetGibToken()
{
    try {
        $postdata = http_build_query(
            array(
                'assoscmd' => 'cfsession',
                'rtype' => 'json',
                'fskey' => 'intvrg.fix.session',
                'fuserid' => 'INTVRG_FIX'
            )
        );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);
        $result = file_get_contents('https://ivd.gib.gov.tr/tvd_server/assos-login', false, $context);
        $datas = json_decode($result, false);

        return $datas;


    } catch (Exception $e) {
        $return = array(
            'status' => 'error',
            'message' => $e->getMessage() . ' line => ' . $e->getLine()
        );
        return (object)$return;
    }
}


function getUserBranchInfoWithUserID($id)
{
    ## Get Branch Info

    $user = User::find($id);

    if ($user->user_type == 'Acente') {
        $agency = Agencies::find($user->agency_code);
        $branch = [
            'id' => $agency->id,
            'code' => $agency->agency_code,
            'city' => $agency->city,
            'name' => $agency->agency_name,
            'type' => 'ŞUBE',
            'type2' => 'Acente'
        ];
    } else {
        $tc = TransshipmentCenters::find($user->tc_code);
        $branch = [
            'id' => $tc->id,
            'city' => $tc->city,
            'name' => $tc->tc_name,
            'type' => 'TRM.',
            'type2' => 'Aktarma',
        ];
    }


    return $branch;
}

function vkn_confirm($vkn, $vd, $il)
{
    $token = GetGibToken();

    $jp = json_encode(array(
        "dogrulama" => array(
            "vkn1" => $vkn,
            "tckn1" => "",
            "iller" => $il,
            "vergidaireleri" => $vd
        )
    ));

    $data_string = "cmd=vergiNoIslemleri_vergiNumarasiSorgulama&callid=ff81dd010b12d-8&pageName=R_INTVRG_INTVD_VERGINO_DOGRULAMA&token=" . $token->token . "&jp=" . $jp;

    $postdata = http_build_query(
        array(
            'cmd' => 'vergiNoIslemleri_vergiNumarasiSorgulama',
            'callid' => 'ff81dd010b12d-8',
            'pageName' => 'R_INTVRG_INTVD_VERGINO_DOGRULAMA',
            'token' => $token->token,
            'jp' => $jp
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context = stream_context_create($opts);
    $result = file_get_contents('https://ivd.gib.gov.tr/tvd_server/dispatch', false, $context);
    $result = json_decode($result);

    if (isset($result->data->unvan))
        return ['status' => 1, 'data' => $result->data];
    else
        return ['status' => 0, 'message' => 'Bilgiler Gelir İdaresi Başkanlığı Tarafından Onaylanmadı, Lütfen bilgileri kontrol edip tekrar deneyiniz!'];
}

function CreateNewCurrentNumber()
{
    $codeControl = true;
    $current_code = '';

    # => create current code and code control
    while ($codeControl != false) {
        $current_code = rand(111111111, 999999999);
        $codeControl = DB::table('currents')
            ->where('current_code', $current_code)
            ->exists();
    }

    return $current_code;
}

function PaymentTypeColor($paymentType)
{
    switch ($paymentType) {
        case 'Gönderici Ödemeli':
            $color = '<b class="text-alternate">GÖ</b>';
            break;

        case 'Alıcı Ödemeli':
            $color = '<b class="text-dark">AÖ</b>';
            break;

        case 'PÖCH':
            $color = '<b class="text-primary">PÖCH</b>';
            break;

        default:
            $color = '<b class="text-warning">' . $paymentType . '</b>';
            break;
    }

    return $color;
}
