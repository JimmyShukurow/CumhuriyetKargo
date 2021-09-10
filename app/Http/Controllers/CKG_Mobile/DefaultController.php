<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Debits;
use App\Models\Departments;
use App\Models\RegioanalDirectorates;
use App\Models\TicketDetails;
use App\Models\Tickets;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DefaultController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $password = $data['password'];

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $user = Auth::user();
            $success['token'] = $user->createToken("CKG_Mobile_Login")->accessToken;

            return response()
                ->json(['status' => 1, 'role' => Auth::user()->role_id, 'token' => $success['token']], 200);
        }

        return response()
            ->json(['status' => 0, 'message' => 'Hatalı kullanıcı adı veya şifre!'], 200);
    }

    public function user()
    {
        $data['general_info'] = DB::table('view_users_all_info')
            ->select(['id', 'name_surname', 'email', 'phone', 'created_at', 'user_type', 'display_name', 'agency_code', 'tc_code', 'branch_city', 'branch_district', 'branch_district', 'branch_name'])
            ->where('id', Auth::user()->id)
            ->first();

        $data['regional_directorates'] = DB::table('regional_districts')
            ->where('city', $data['general_info']->branch_city)
            ->where('district', $data['general_info']->branch_district)
            ->first();

        $data['regional_directorates'] = DB::table('regional_directorates')
            ->select(['name', 'phone', 'city', 'district', 'neighborhood', 'adress'])
            ->first();

        return response()
            ->json($data, 200);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->authUserToken()->delete();

            return response()
                ->json(['status' => 1, 'message' => 'İşlem başarılı, çıkış yapıldı!'], 200);
        }
    }

    public function getDefaultData($val = '', Request $request)
    {
        switch ($val) {
            case 'Departments':
                $data['departments'] = Departments::all();
                break;

            case 'GetNotifications':
                $array = array();
                $notifications = DB::table('notifications')
                    ->where('notifiable_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->limit(30)
                    ->get();

                foreach ($notifications as $key) {

                    $xData = json_decode($key->data);

                    if (isset($xData->ticket_id))
                        $ticket_id = $xData->ticket_id;
                    else
                        $ticket_id = '';

                    $array[] = [
                        'id' => $key->id,
                        "notifiable_id" => $key->notifiable_id,
                        'data' => ['notification' => $xData->notification, 'link' => $xData->link, 'ticket_id' => $ticket_id],
                        'read_at' => $key->read_at,
                        'created_at' => $key->created_at,
                    ];
                }

                $data['notifications'] = $array;
                break;

            case 'GetTickets':
                $data['tickets'] = DB::table('tickets')
                    ->join('departments', 'departments.id', '=', 'tickets.department_id')
                    ->select(['tickets.*', 'departments.department_name'])
                    ->where('user_id', Auth::id())
                    ->limit(30)
                    ->orderBy('updated_at', 'desc')
                    ->get();

                foreach ($data['tickets'] as $key) {
                    $key->title = '#D-' . $key->id . ' - ' . $key->message;
                    $key->created_at = $key->created_at . ' (' . Carbon::parse($key->created_at)->diffForHumans() . ')';

                }


                break;

            case 'GetNotificationCount':
                $count = Auth::user()->unReadnotifications->count();
                return response()
                    ->json(['count' => $count], 200);
                break;

            default:
                $data = 'no-case';
                break;
        }

        return response()
            ->json($data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);

    }

    public function defaultTransaction($val = '', Request $request)
    {
        switch ($val) {

            case 'ChangePassword':
                $rules = [
                    'password' => 'required',
                    'passwordNew' => 'required|regex:/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/u',
                    'passwordNewAgain' => 'required|same:passwordNew'
                ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 0, 'errors' => $validator->getMessageBag()->toArray()], 200);

                $password_check = User::where('id', Auth::id())
                    ->where("password", Hash::make($request->password))
                    ->first();

                if (Hash::check($request->password, Auth::user()->password)) {

                    $update = User::find(Auth::id())
                        ->update([
                            'password' => Hash::make($request->passwordNew)
                        ]);

                    if ($update) {
                        GeneralLog('Şifre değişikliği yapıldı.');
                        $data = ['status' => 1, 'message' => 'Şifreniz başarıyla değiştirildi!'];
                    } else
                        $data = ['status' => 0, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!'];

                } else
                    $data = ['status' => 0, 'message' => 'Eski şifreniz hatalı!'];
                break;

            case 'GetTicketDetails':
                $ticket_id = $request->ticket_id;
                $is_message = "0";

                if ($ticket_id == '')
                    $data = ['status' => 0, 'message' => 'ticket id is missing!'];
                else {
                    $array = array();

                    $data['ticket_details'] = DB::table('ticket_details')
                        ->select(['ticket_details.*', 'view_users_all_info.name_surname', 'view_users_all_info.email', 'view_users_all_info.user_type', 'view_users_all_info.display_name', 'view_users_all_info.branch_city', 'view_users_all_info.branch_district', 'view_users_all_info.branch_name'])
                        ->join('view_users_all_info', 'view_users_all_info.id', '=', 'ticket_details.user_id')
                        ->where('ticket_id', $ticket_id)
                        ->orderBy('ticket_details.created_at', 'desc')
                        ->limit(50)
                        ->get();

                    # Get First Message START
                    $realTicket = DB::table('tickets')
                        ->join('view_users_all_info', 'view_users_all_info.id', '=', 'tickets.user_id')
                        ->select(['tickets.*', 'view_users_all_info.name_surname', 'view_users_all_info.email', 'view_users_all_info.user_type', 'view_users_all_info.display_name', 'view_users_all_info.branch_city', 'view_users_all_info.branch_district', 'view_users_all_info.branch_name'])
                        ->where('tickets.id', $ticket_id)
                        ->first();

                    $userID = Auth::id();

                    $file1 = FileUrlGenerator($request->file1);
                    $file2 = FileUrlGenerator($request->file2);
                    $file3 = FileUrlGenerator($request->file3);
                    $file4 = FileUrlGenerator($request->file4);

                    $array[] = [
                        'id' => "0",
                        'ticket_id' => "$ticket_id",
                        'user_id' => "$userID",
                        //                        'message' => substr($realTicket->message, '0', 1) != '#' ? strip_tags($realTicket->message) : $realTicket->message,
                        'message' => $realTicket->message,
                        'file1' => $file1,
                        'file2' => $file2,
                        'file3' => $file3,
                        'file4' => $file4,
                        'created_at' => $realTicket->created_at,
                        'updated_at' => $realTicket->updated_at,
                        'name_surname' => $realTicket->name_surname,
                        'email' => $realTicket->email,
                        'user_type' => $realTicket->user_type,
                        'display_name' => $realTicket->display_name,
                        'branch_city' => $realTicket->branch_city,
                        'branch_district' => $realTicket->branch_district,
                        'branch_name' => $realTicket->branch_name,
                        'is_massage' => $is_message,
                        'time_for_humans' => Carbon::parse($realTicket->created_at)->diffForHumans()
                    ];
                    # Get First Message END

                    foreach ($data['ticket_details'] as $key) {

                        if (isRedirectedMessage($key->message) != null) {
                            $message = " Bu destek talebi <b>$key->name_surname</b>
                                    <b>($key->display_name)</b>
                                    tarafından
                                    <b>" . \Carbon\Carbon::parse($key->created_at)->translatedFormat('d F D Y H:i') . "</b>
                                    tarihinde
                                    <b>" . isRedirectedMessage($key->message) . "</b> departmanına
                                    yönlendirildi.";

                            $is_message = "1";
                        } else if (isUpdatedStatusMessage($key->message) != null) {

                            $message = " Bu destek talebi <b>$key->name_surname</b>
                                    <b>($key->display_name)</b>
                                    tarafından
                                    <b>" . \Carbon\Carbon::parse($key->created_at)->translatedFormat('d F D Y H:i') . "</b>
                                    tarihinde destek talebinin durumu
                                    <b style='color:red;'>" . isRedirectedMessage($key->message) . "</b> olarak güncellendi.";
                            $is_message = "1";
                        } else {
                            $message = $key->message;
                            $is_message = "0";
                        }

                        $file1 = FileUrlGenerator($key->file1);
                        $file2 = FileUrlGenerator($key->file2);
                        $file3 = FileUrlGenerator($key->file3);
                        $file4 = FileUrlGenerator($key->file4);

                        $array[] = [
                            'id' => "$key->id",
                            'ticket_id' => "$key->ticket_id",
                            'user_id' => "$key->user_id",
                            // 'message' => substr($key->message, '0', 1) != '#' ? strip_tags($key->message) : $key->message,
                            'message' => $message,
                            'file1' => $file1,
                            'file2' => $file2,
                            'file3' => $file3,
                            'file4' => $file4,
                            'created_at' => $key->created_at,
                            'updated_at' => $key->updated_at,
                            'name_surname' => $key->name_surname,
                            'email' => $key->email,
                            'user_type' => $key->user_type,
                            'display_name' => $key->display_name,
                            'branch_city' => $key->branch_city,
                            'branch_district' => $key->branch_district,
                            'branch_name' => $key->branch_name,
                            'is_massage' => $is_message,
                            'time_for_humans' => Carbon::parse($key->created_at)->diffForHumans()
                        ];

                    }
                    $data['ticket_details'] = $array;
                }

                break;

            case 'ReplyTicket':

                $rules = [
                    'ticket_id' => 'required',
                    'message' => 'required',
                    'file1' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
                ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 0, 'errors' => $validator->getMessageBag()->toArray()], 200);


                # token => ticked_id
                $ticket_id = $request->ticket_id;

                $ticket = Tickets::where('id', $ticket_id)->first();
                if ($ticket->user_id != Auth::id())
                    $data = ['result' => 'Ticket Not Found!'];
                else {


                    global $file1, $file2, $file3, $file4;

                    if ($request->hasFile('file1')) {
                        $file1 = $request->file('file1')->getClientOriginalName() . '_' . uniqid() . '.' . $request->file1->getClientOriginalExtension();
                        $request->file1->move(public_path('backend/assets/ticket_files'), $file1);
                    }

                    $insert = TicketDetails::create([
                        'ticket_id' => $ticket_id,
                        'user_id' => Auth::id(),
                        'message' => $request->message . '<br> <br> <br> <i><b class="text-dark"> Sent From CKG-Mobile. </b></i>',
                        'file1' => $file1,
                        'status' => 'AÇIK'
                    ]);

                    if ($insert) {

                        updateTicketTime($ticket_id);

                        #update ticket Status to Opened
                        $update = Tickets::find($ticket_id)
                            ->update([
                                'status' => 'AÇIK'
                            ]);

                        $ticket = Tickets::where('id', $ticket_id)->first();
                        $properties = [
                            'Başlık' => $ticket->title,
                            'Yanıt' => $request->message,
                            'Bağlantı' => route('systemSupport.TicketDetails', ['TicketID' => $ticket_id])
                        ];

                        activity()
                            ->performedOn($ticket)
                            ->withProperties($properties)
                            ->inLog('Ticket Reply')
                            ->log('Destek talebi yanıtlandı');

                        $data = ['status' => 1, 'message' => 'Yanıt başarıyla gönderildi.'];

                    } else
                        $data = ['status' => 0, 'message' => 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin.'];
                }

                break;

            case 'CreateTicket':

                $rules = [
                    'department' => 'required',
                    'title' => 'required|max:75',
                    'priority' => 'required|max:10',
                    'message' => 'required',
                    'file1' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
                ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 0, 'errors' => $validator->getMessageBag()->toArray()], 200);

                global $file1;

                if ($request->hasFile('file1')) {
                    $file1 = $request->file('file1')->getClientOriginalName() . '_' . uniqid() . '.' . $request->file1->getClientOriginalExtension();
                    $request->file1->move(public_path('backend/assets/ticket_files'), $file1);
                }

                $insert = Tickets::create([
                    'user_id' => Auth::id(),
                    'title' => $request->title,
                    'department_id' => $request->department,
                    'priority' => $request->priority,
                    'message' => $request->message,
                    'file1' => $file1,
                    'status' => 'AÇIK'
                ]);

                if ($insert) {

                    $department = Departments::where('id', $request->department)->first();
                    $properties = [
                        'Başlık' => $request->title,
                        'Departman' => $department->department_name,
                        'Bağlantı' => route('systemSupport.TicketDetails', ['TicketID' => $insert->id])
                    ];

                    GeneralLog('Destek talebi oluşturuldu.', $properties);

                    return response()
                        ->json(['status' => 1, 'Destek talebiniz oluşturuldu, ilgili departman en kısa süre içerisinde mesajınıza dönüş yapacaktır.'], 200);

                } else
                    return response()
                        ->json(['status' => 0, 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin'], 200);
                break;

            case 'MarkAsRead':
                $transaction = Auth::user()
                    ->unreadNotifications
                    ->when($request->id, function ($query) use ($request) {
                        return $query->where('id', $request->id);
                    })
                    ->markAsRead();

                $count = Auth::user()->unReadnotifications->count();
                # return response()->noContent();
                return response()->json(['status' => 1, 'remaining_notifications' => $count], 200);
                break;

            default:
                $data = 'no-case';
                break;
        }

        return response()
            ->json($data, 200);

    }

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
