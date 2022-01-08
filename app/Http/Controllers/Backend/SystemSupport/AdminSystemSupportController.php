<?php

namespace App\Http\Controllers\Backend\SystemSupport;

use App\Http\Controllers\Controller;
use App\Models\DepartmentRoles;
use App\Models\Departments;
use App\Models\Roles;
use App\Models\SubModules;
use App\Models\TicketDetails;
use App\Models\Tickets;
use App\Models\User;
use App\Notifications\TicketNotify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use function Symfony\Component\String\b;
use App\Models\RegioanalDirectorates;

class AdminSystemSupportController extends Controller
{
    public function index()
    {
        GeneralLog('[Admin] Ticket görüntülendi.');

        $data['tickets'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->select('tickets.*', 'departments.department_name')
            ->orderBy('id', 'desc')
            ->paginate(50);

        $data['count'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->select('tickets.*', 'departments.department_name')
            ->orderBy('id', 'desc')
            ->count();

        $data['title'] = SubModules::where('link', 'admin.systemSupport.index')->first();

        ## departments for roles
        $data['departments'] = DB::table('department_roles')
            ->select(['departments.id', 'departments.department_name'])
            ->join('departments', 'department_roles.department_id', '=', 'departments.id')
            ->where('department_roles.role_id', Auth::user()->role_id)
            ->get();

        $data['regional_directorates'] = RegioanalDirectorates::all();

        $countRequestDepartment = DB::table('department_roles')
            ->where('role_id', Auth::user()->role_id)
            ->get();


        return view('backend.system_support.admin.index', compact('data'));
    }

    public function getTickets(Request $request)
    {
        ## departments for roles
        $allPermDepartments = DB::table('department_roles')
            ->select(['department_roles.department_id'])
            ->where('role_id', Auth::user()->role_id)
            ->get()->pluck('department_id')->toArray();

        $startDate = $request->has('start_date') ? $request->start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $finish_date = $request->has('finish_date') ? $request->finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $department_id = $request->department != '' ? [$request->department] : $allPermDepartments;
        $status = $request->status;
        $priority = $request->priority;
        $name_surname = $request->name_surname;
        $title = $request->title;
        $dateFilter = $request->date_filter;
        $regionArray = $request->selected_region != '' ? $request->selected_region : 'null';
        $user_id = Decrypte4x($request->x_token);
        $ticket_no = $request->ticket_no;
        $redirected = $request->redirected;

        $role = User::where('id', $user_id)->first();
        ## departments for roles
        $countRequestDepartment = DB::table('department_roles')
            ->where('role_id', $role->role_id)
            ->where('department_id', $department_id)
            ->get();

        if ($countRequestDepartment === null) {
            $department_id = -1;
            $department_id = [$department_id];
        }


        $tickets = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select([
                'tickets.*',
                'departments.department_name',
                'view_users_all_info.name_surname',
                'view_users_all_info.branch_city',
                'view_users_all_info.branch_district',
                'view_users_all_info.branch_name',
                'view_users_all_info.user_type',
                'view_users_all_info.display_name',
                'tickets.created_at',
                'view_region_name_and_districts.region_id'
            ])
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->whereRaw('region_id in(' . $regionArray . ')');

        return datatables()->of($tickets)
            ->editColumn('status', 'backend.system_support.admin.columns.status')
            ->editColumn('priority', 'backend.system_support.admin.columns.priority')
            ->editColumn('title', 'backend.system_support.admin.columns.title')
            ->editColumn('detail', 'backend.system_support.admin.columns.detail')
            ->editColumn('name_surname', 'backend.system_support.admin.columns.creator')
            ->editColumn('redirected', function ($log) {
                return $log->redirected == '1' ? '<b>Evet</b>' : '<b>Hayır</b>';
            })
            ->rawColumns(['status', 'priority', 'title', 'detail', 'name_surname', 'redirected'])
            ->make(true);
    }

    public function pageRowCount(Request $request)
    {
        ## departments for roles
        $allPermDepartments = DB::table('department_roles')
            ->select(['department_roles.department_id'])
            ->where('role_id', Auth::user()->role_id)
            ->get()->pluck('department_id')->toArray();

        $startDate = $request->has('start_date') ? $request->start_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $finish_date = $request->has('finish_date') ? $request->finish_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $department_id = $request->department != '' ? [$request->department] : $allPermDepartments;
        $status = $request->status;
        $priority = $request->priority;
        $name_surname = $request->name_surname;
        $title = $request->title;
        $dateFilter = $request->date_filter;
        $regionArray = $request->selected_regions != '' ? $request->selected_regions : 'null';
        $user_id = Decrypte4x($request->x_token);
        $ticket_no = $request->ticket_no;
        $redirected = $request->redirected;

        $role = User::where('id', $user_id)->first();
        ## departments for roles
        $countRequestDepartment = DB::table('department_roles')
            ->where('role_id', $role->role_id)
            ->where('department_id', $department_id)
            ->get();

        if ($countRequestDepartment === null) {
            $department_id = -1;
            $department_id = [$department_id];
        }

        $counter['all'] = DB::table('tickets')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select(['tickets.*', 'view_users_all_info.name_surname'])
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->whereRaw('region_id in(' . $regionArray . ')')
            ->count();

        $counter['acik'] = DB::table('tickets')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select(['tickets.*', 'view_users_all_info.name_surname'])
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->where('tickets.status', 'AÇIK')
            ->whereRaw('region_id in(' . $regionArray . ')')
            ->count();

        $counter['beklemede'] = DB::table('tickets')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select(['tickets.*', 'view_users_all_info.name_surname'])
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->where('tickets.status', 'BEKLEMEDE')
            ->whereRaw('region_id in(' . $regionArray . ')')
            ->count();

        $counter['kapali'] = DB::table('tickets')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select(['tickets.*', 'view_users_all_info.name_surname'])
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->where('tickets.status', 'KAPALI')
            ->whereRaw('region_id in(' . $regionArray . ')')
            ->count();

        $counter['cevaplandi'] = DB::table('tickets')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->join('view_region_name_and_districts', function ($join) {
                $join->on('view_users_all_info.branch_city', '=', 'view_region_name_and_districts.city');
                $join->on('view_users_all_info.branch_district', '=', 'view_region_name_and_districts.district');
            })
            ->select(['tickets.*', 'view_users_all_info.name_surname'])
            ->whereRaw($dateFilter == 'true' ? "tickets.created_at between '" . $startDate . "'  and '" . $finish_date . "'" : '1 > 0')
            ->whereIn('tickets.department_id', $department_id)
            ->whereRaw($status != '' ? 'tickets.status=\'' . $status . '\'' : '1 > 0')
            ->whereRaw($redirected != '' ? 'tickets.redirected=\'' . $redirected . '\'' : '1 > 0')
            ->whereRaw($ticket_no != '' ? 'tickets.id=' . $ticket_no : '1 > 0')
            ->whereRaw($priority != '' ? 'tickets.priority=\'' . $priority . '\'' : '1 > 0')
            ->whereRaw($name_surname != '' ? 'view_users_all_info.name_surname like \'%' . $name_surname . '%\'' : '1 > 0')
            ->whereRaw($title != '' ? 'tickets.title like \'%' . $title . '%\'' : '1 > 0')
            ->where('tickets.status', 'CEVAPLANDI')
            ->whereRaw('region_id in(' . $regionArray . ')')
            ->count();

        return response()->json($counter, 200);

    }

    public function ticketDetails($TicketID)
    {
        $ticket = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->join('view_users_all_info', 'tickets.user_id', '=', 'view_users_all_info.id')
            ->select('tickets.*', 'departments.department_name', 'view_users_all_info.name_surname', 'view_users_all_info.display_name',
                'view_users_all_info.branch_city', 'view_users_all_info.branch_district', 'view_users_all_info.branch_name', 'view_users_all_info.phone',
                'view_users_all_info.user_type')
            ->orderBy('id', 'desc')
            ->where('tickets.id', $TicketID)
            ->first(10);

        $ticket_details = DB::table('ticket_details')
            ->where('ticket_id', $TicketID)
            ->join('view_users_all_info', 'ticket_details.user_id', '=', 'view_users_all_info.id')
            ->select('ticket_details.*', 'view_users_all_info.name_surname', 'view_users_all_info.display_name',
                'view_users_all_info.branch_city', 'view_users_all_info.branch_district', 'view_users_all_info.branch_name', 'view_users_all_info.user_type')
            ->orderBy('id', 'desc')
            ->get();


        if ($ticket === null)
            return redirect(route('systemSupport.myTickets'))->with('error', 'Aradığınız destek talebi bulunamadı!');

        $model = Tickets::where('id', $TicketID)->first();
        $properties = [
            'Ticket Title' => $ticket->title,
            'Creator Name' => $ticket->name_surname,
            'Creator City' => $ticket->branch_city,
            'Creator District' => $ticket->branch_district,
            'Creator Branch Name' => $ticket->branch_name,
            'Creator Type' => $ticket->user_type,
            'Link' => \route('admin.systemSupport.TicketDetails', [$ticket->id])
        ];
        activity()
            ->withProperties($properties)
            ->performedOn($model)
            ->inLog('Ticket Views')
            ->log("[Admin] Ticket Detayı Görüntülendi");

        $view_details = DB::table('activity_log')
            ->join('view_users_all_info', 'activity_log.causer_id', '=', 'view_users_all_info.id')
            ->where('log_name', 'Ticket Views')
            ->where('subject_id', $ticket->id)
            ->select('activity_log.*', 'view_users_all_info.name_surname', 'view_users_all_info.display_name',
                'view_users_all_info.branch_city', 'view_users_all_info.branch_district', 'view_users_all_info.branch_name', 'view_users_all_info.user_type')
            ->orderBy('created_at', 'desc')
            ->get();

        $ticket_logs = DB::table('activity_log')
            ->join('view_users_all_info', 'activity_log.causer_id', '=', 'view_users_all_info.id')
            ->where('subject_type', 'App\Models\Tickets')
            ->where('subject_id', $ticket->id)
            ->whereNotIn('log_name', ['Ticket Views', 'default'])
            ->select('activity_log.*', 'view_users_all_info.name_surname', 'view_users_all_info.display_name',
                'view_users_all_info.branch_city', 'view_users_all_info.branch_district', 'view_users_all_info.branch_name', 'view_users_all_info.user_type')
            ->orderBy('created_at', 'desc')
            ->get();

        $view_details_summery = DB::select("SELECT view_ticket_view_details_summary.*, ( SELECT created_at FROM activity_log WHERE causer_id = view_ticket_view_details_summary.causer_id AND log_name = 'Ticket Views' ORDER BY created_at DESC LIMIT 1 ) AS last_view
                FROM view_ticket_view_details_summary  where subject_id = $ticket->id  ORDER BY last_view desc");

        $view_details_summery_count = DB::select("SELECT COUNT(*) as quantity, SUM(count) as sum FROM view_ticket_view_details_summary where subject_id = $ticket->id");


        foreach ($view_details_summery_count as $k) {
            $quantity = $k->quantity;
            $sum = $k->sum;
        }
        $departments = Departments::all();

        return view('backend.system_support.admin.details', compact(['ticket', 'ticket_details', 'view_details', 'view_details_summery', 'sum', 'quantity', 'ticket_logs', 'departments']));
    }

    # admin repyl ticket
    public function replyTicket(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'message' => 'required',
            'status' => 'required',
            'file1' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file2' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file3' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file4' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096'
        ]);
        # token => ticked_id
        $ticket_id = Decrypte4x($request->token);

        ### Admin user department control
        $is_there_department_permission = DB::table('view_department_roles_details')
            ->where('role_id', Auth::user()->role_id)
            ->count();

        # status control
        $ticketStatus = collect(['Açık', 'Kapalı', 'Beklemede', 'Cevaplandı']);
        if ((!$ticketStatus->contains($request->status)))
            return back()->with('error', 'Lütfen geçerli bir bildirim durumu  seçin! (Örn:Açık, Kapalı, Beklemede, Cevaplandı)');


        if ($is_there_department_permission == 0)
            return redirect(route('systemSupport.myTickets'))->with('error', 'Sadece departman yetkilileri yanıt verebilir!');


        global $file1, $file2, $file3, $file4;

        if ($request->hasFile('file1')) {
            $file1 = uniqid() . '_' . uniqid() . '.' . $request->file1->getClientOriginalExtension();
            $request->file1->move(public_path('backend/assets/ticket_files'), $file1);
        }
        if ($request->hasFile('file2')) {
            $file2 = uniqid() . '_' . uniqid() . '.' . $request->file2->getClientOriginalExtension();
            $request->file2->move(public_path('backend/assets/ticket_files'), $file2);
        }
        if ($request->hasFile('file3')) {
            $file3 = uniqid() . '_' . uniqid() . '.' . $request->file3->getClientOriginalExtension();
            $request->file3->move(public_path('backend/assets/ticket_files'), $file3);
        }
        if ($request->hasFile('file4')) {
            $file4 = uniqid() . '_' . uniqid() . '.' . $request->file4->getClientOriginalExtension();
            $request->file4->move(public_path('backend/assets/ticket_files'), $file4);
        }

        $insert = TicketDetails::create([
            'ticket_id' => $ticket_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'file1' => $file1,
            'file2' => $file2,
            'file3' => $file3,
            'file4' => $file4,
            'status' => 'AÇIK'
        ]);

        if ($insert) {

            #update ticket Status to Opened
            $update = Tickets::find($ticket_id)
                ->update([
                    'status' => tr_strtoupper($request->status)
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

            updateTicketTime($ticket_id);

            ## send mail to ticket user
//            $user = DB::table('tickets')
//                ->join('users', 'tickets.user_id', '=', 'users.id')
//                ->select('users.name_surname', 'users.email')
//                ->where('tickets.id', $ticket_id)
//                ->first();

//            $data['title'] = 'Destek Talebiniz Yanıtlandı!';
//            $data['body'] = 'Sayın <b>' . $user->name_surname . '</b>, <b>' . $ticket->title . '</b> başlıklı destek talebiniz <b>yanıtlanmıştır</b>. Detayları görmek için lütfen portalı ziyaret edin!';
//            $data['link'] = \route('systemSupport.TicketDetails', $ticket->id);
//            $data['reading_time'] = '2';
//
        //    Mail::to($user->email)
        //        ->send(new SendMail($data));

            # Notification
            User::find($ticket->user_id)
                ->notify(new TicketNotify('"' . $ticket->title . '"' . ' başlıklı destek talebiniz yanıtlandı.', route('systemSupport.TicketDetails', $ticket->id), $ticket_id));

            return back()->with('success', 'Yanıt gönderildi.');
        } else {
            $request->flash();
            return back()->with('error', 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin');
        }
    }

    public function redirectTicket(Request $request)
    {
        $request->validate([
            'department' => 'required|numeric',
            'x_token' => 'required'
        ]);

        $user_id = Auth::id();
        $ticket_id = Decrypte4x($request->x_token);
        $department_id = $request->department;

        # department control
        $department = Departments::where('id', $department_id)->first();
        if ($department === null)
            return back()->with('error', 'Lütfen yönlendirmek istediğiniz departmanı seçin!');


        $update = Tickets::find($ticket_id)
            ->update([
                'redirected' => '1',
                'department_id' => $department_id
            ]);


        if ($update) {
            $insert = TicketDetails::create([
                'ticket_id' => $ticket_id,
                'user_id' => Auth::id(),
                'message' => '#### ==> Redirected <== #### to:' . $department->department_name,
                'file1' => '',
                'file2' => '',
                'file3' => '',
                'file4' => '',
                'status' => 'AÇIK'
            ]);

            if ($insert) {

                updateTicketTime($ticket_id);

                $user = DB::table('tickets')
                    ->join('users', 'tickets.user_id', '=', 'users.id')
                    ->select('users.name_surname', 'users.email')
                    ->where('tickets.id', $ticket_id)
                    ->first();

                $data['title'] = 'Destek Talebiniz Yönlendirildi!';
                $data['body'] = 'Sayın <b>' . $user->name_surname . '</b>, Destek talebiniz <b>' . $department->department_name . '</b> departmanına yönlendirimiştir. Yetkililer en kısa sürede destek talebinize dönüş sağlayacaktır. Detayları görmek için lütfen portalı ziyaret edin!';
                $data['link'] = '';
                $data['reading_time'] = '2';

//                Mail::to($user->email)
//                    ->send(new SendMail($data));

                $ticket = Tickets::where('id', $ticket_id)->first();
                activity()
                    ->performedOn($ticket)
                    ->inLog('Ticket Redirect')
                    ->log('Destek talebi ' . Auth::user()->name_surname . ' tarafından, ' . $department->department_name . ' departmanına yönlendirildi.');

                return redirect(route('admin.systemSupport.index'))
                    ->with('success', 'Destek talebi başarıyla ' . $department->department_name . ' departmanına yönlendirildi!');

            } else
                return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');

        } else
            return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }


    public function updateStatusTicket(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'x_token' => 'required'
        ]);

        $user_id = Auth::id();
        $ticket_id = Decrypte4x($request->x_token);

        # status control
        $ticketStatus = collect(['Açık', 'Kapalı', 'Beklemede', 'Cevaplandı']);
        if ((!$ticketStatus->contains($request->status)))
            return back()->with('error', 'Lütfen geçerli bir bildirim durumu  seçin! (Örn:Açık, Kapalı, Beklemede, Cevaplandı)');


        $update = Tickets::find($ticket_id)
            ->update([
                'status' => tr_strtoupper($request->status)
            ]);


        if ($update) {

            updateTicketTime($ticket_id);

            $insert = TicketDetails::create([
                'ticket_id' => $ticket_id,
                'user_id' => Auth::id(),
                'message' => '#### ==> Status Updated <== #### to:' . $request->status,
                'file1' => '',
                'file2' => '',
                'file3' => '',
                'file4' => '',
                'status' => 'AÇIK'
            ]);

            if ($insert) {

                $ticket = Tickets::where('id', $ticket_id)->first();
                activity()
                    ->performedOn($ticket)
                    ->inLog('Ticket Updated')
                    ->log('Destek talebi ' . Auth::user()->name_surname . ' tarafından, durumu ' . $request->status . ' olarak güncellendi.');

                return redirect(route('admin.systemSupport.index'))
                    ->with('success', 'Destek talebi başarıyla ' . $request->status . ' olarak güncellendi!');

            } else
                return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');

        } else
            return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');


    }

}
