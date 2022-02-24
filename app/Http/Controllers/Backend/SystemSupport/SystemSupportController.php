<?php

namespace App\Http\Controllers\Backend\SystemSupport;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Departments;
use App\Models\TicketDetails;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class SystemSupportController extends Controller
{
    public function addTicket()
    {
        $data['departments'] = Departments::all();
        return view('backend.system_support.create', compact('data'));
    }

    public function createTicket(Request $request)
    {
        $request->validate([
            'department' => 'required',
            'title' => 'required|max:75',
            'priority' => 'required|max:10',
            'message' => 'required',
            'file1' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file2' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file3' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file4' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096'
        ]);

        global $file1, $file2, $file3, $file4;


        if ($request->hasFile('file1')) {
            $file1 = getJustFileName($request->file1->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file1->getClientOriginalExtension();
            $request->file1->move(public_path('files/ticket_files'), $file1);
        }
        if ($request->hasFile('file2')) {
            $file2 = getJustFileName($request->file2->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file2->getClientOriginalExtension();
            $request->file2->move(public_path('files/ticket_files'), $file2);
        }
        if ($request->hasFile('file3')) {
            $file3 = getJustFileName($request->file3->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file3->getClientOriginalExtension();
            $request->file3->move(public_path('files/ticket_files'), $file3);
        }
        if ($request->hasFile('file4')) {
            $file4 = getJustFileName($request->file4->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file4->getClientOriginalExtension();
            $request->file4->move(public_path('files/ticket_files'), $file4);
        }

        $insert = Tickets::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'department_id' => $request->department,
            'priority' => $request->priority,
            'message' => $request->message,
            'file1' => $file1,
            'file2' => $file2,
            'file3' => $file3,
            'file4' => $file4,
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

            return redirect(route('systemSupport.myTickets'))
                ->with('success', 'Destek talebiniz oluşturuldu, ilgili departman en kısa süre içerisinde mesajınıza dönüş yapacaktır.');
        } else {
            $request->flash();
            return back()->with('error', 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin');
        }
    }

    public function myTickets()
    {
        GeneralLog('Destek taleplerim sayfası görüntülendi.');

        $data['tickets'] = DB::table('tickets')
            ->join('departments', 'tickets.department_id', '=', 'departments.id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->select('tickets.*', 'departments.department_name', 'users.name_surname')
            ->orderBy('id', 'desc')
            ->where('user_id', Auth::id())
            ->paginate(10);

        return view('backend.system_support.index', compact('data'));
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

        if ($ticket === null || $ticket->user_id != Auth::id())
            return redirect(route('systemSupport.myTickets'))->with('error', 'Aradığınız destek talebi bulunamadı!');

        return view('backend.system_support.details', compact(['ticket', 'ticket_details']));
    }

    public function replyTicket(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'message' => 'required',
            'file1' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file2' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file3' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096',
            'file4' => 'nullable|mimes:jpg,gif,jpeg,png,doc,xls,pdf,txt,docx,xlsx|max:4096'
        ]);
        # token => ticked_id
        $ticket_id = Decrypte4x($request->token);

        $ticket = Tickets::where('id', $ticket_id)->first();
        if ($ticket->user_id != Auth::id())
            return redirect(route('systemSupport.myTickets'))->with('error', 'Aradığınız destek talebi bulunamadı!');


        global $file1, $file2, $file3, $file4;


        if ($request->hasFile('file1')) {
            $file1 = getJustFileName($request->file1->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file1->getClientOriginalExtension();
            $request->file1->move(public_path('files/ticket_files'), $file1);
        }
        if ($request->hasFile('file2')) {
            $file2 = getJustFileName($request->file2->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file2->getClientOriginalExtension();
            $request->file2->move(public_path('files/ticket_files'), $file2);
        }
        if ($request->hasFile('file3')) {
            $file3 = getJustFileName($request->file3->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file3->getClientOriginalExtension();
            $request->file3->move(public_path('files/ticket_files'), $file3);
        }
        if ($request->hasFile('file4')) {
            $file4 = getJustFileName($request->file4->getClientOriginalName()) . '_' . uniqid() . '_' . uniqid() . '.' . $request->file4->getClientOriginalExtension();
            $request->file4->move(public_path('files/ticket_files'), $file4);
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

            return back()->with('success', 'Yanıt gönderildi.');
        } else {
            $request->flash();
            return back()->with('error', 'Bir hata oluştu, Lütfen daha sonra tekrar deneyin');
        }
    }
}
