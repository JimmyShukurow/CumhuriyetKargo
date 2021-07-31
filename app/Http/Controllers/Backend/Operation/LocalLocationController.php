<?php

namespace App\Http\Controllers\Backend\Operation;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocalLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['agencies'] = Agencies::all();
        $data['gm_users'] = DB::table('users')
            ->where('agency_code', 1)
            ->get();
        $data['cities'] = Cities::all();

        return view('backend.operation.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getLocation(Request $request)
    {
        #getLocation

        $record = $request->record;
        $status = $request->status;
        $agency = $request->agency;
        $name = $request->name;
        $currentCode = str_replace([' ', '_'], '', $request->currentCode);
        $creatorUser = $request->creatorUser;
        $category = $request->category != -1 ? $request->category : '';
        $confirmed = $request->confirmed;

        $currents = DB::table('currents')
            ->join('agencies', 'currents.agency', '=', 'agencies.id')
            ->join('users', 'currents.created_by_user_id', '=', 'users.id')
            ->select(['currents.*', 'agencies.agency_name', 'users.name_surname'])
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($agency ? 'agency=' . $agency : '1 > 0')
            ->whereRaw($creatorUser ? 'created_by_user_id=' . $creatorUser : '1 > 0')
            ->whereRaw($status ? "currents.`status`='" . $status . "'" : '1 > 0')
            ->whereRaw($category ? "currents.`category`='" . $category . "'" : '1 > 0')
            ->whereRaw($request->filled('confirmed') ? "confirmed='" . $confirmed . "'" : '1 > 0')
            ->whereRaw($name ? "name like '%" . $name . "%'" : '1 > 0')
            ->whereRaw($record == '1' ? 'currents.deleted_at is null' : 'currents.deleted_at is not null')
            ->where('current_type', 'Gönderici');

        return datatables()->of($currents)
            ->editColumn('name', function ($current) {
                return Str::words($current->name, 3, '...');
            })
            ->editColumn('city', function ($current) {
                return $current->city . "/" . $current->district;
            })
            ->setRowId(function ($currents) {
                return "current-item-" . $currents->id;
            })
            ->editColumn('status', function ($currents) {
                return $currents->status == '1' ? 'Aktif' : 'Pasif';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->rawColumns(['edit'])
            ->make(true);

    }
}
