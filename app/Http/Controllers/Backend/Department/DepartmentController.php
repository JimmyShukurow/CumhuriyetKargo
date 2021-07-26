<?php

namespace App\Http\Controllers\Backend\Department;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\DepartmentRoles;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        GeneralLog('Departmanlar sayfası görüntülendi.');
        $data['departments'] = Departments::all();


        return view('backend.departments.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|max:50',
            'explantion' => 'nullable|max:250'
        ]);

        $store = Departments::create([
            'department_name' => tr_strtoupper($request->department_name),
            'explantion' => tr_strtoupper($request->explantion)
        ]);

        if ($store) return redirect(route('Departments.index'))->with('success', 'Departman başarıyla eklendi');
        else return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
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
        $data = Departments::where('id', $id)->first();
        if ($data === null)
            return redirect(route('Departments.index'))->with('error', 'Düzenlemek istediğiniz departman bulunamadı!');

        return view('backend.departments.edit', compact('data'));
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
        $data = Departments::where('id', $id)->first();
        if ($data === null)
            return redirect(route('Departments.index'))->with('error', 'Düzenlemek istediğiniz departman bulunamadı!');

        $request->validate([
            'department_name' => 'required|max:50',
            'explantion' => 'nullable|max:250'
        ]);

        $update = Departments::find($id)
            ->update([
                'department_name' => tr_strtoupper($request->department_name),
                'explantion' => tr_strtoupper($request->explantion)
            ]);

        if ($update) return redirect(route('Departments.index'))->with('success', 'Departman başarıyla güncellendi');
        else return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Departments::find(intval($id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }


    public function departmentRole()
    {
        $data['departments'] = Departments::all();
        return view('backend.departments.department_role', compact('data'));
    }

    public function getRoles(Request $request, $direktive = 'all')
    {
        if ($direktive == 'all')
            $roles = DB::table('view_role_department_gived')
                ->select('id as key', 'display_name', 'description', 'gived')
                ->get();
        else
            $roles = DB::select("SELECT roles.id as 'key', roles.display_name, roles.description,
            (SELECT COUNT(*) FROM department_roles WHERE role_id = roles.id and department_id =  $direktive ) as gived
             FROM roles");

        return response()->json($roles, 200);
    }

    public function giveRole(Request $request)
    {
        global $create;
        foreach ($request->roles_array as $id) {
            $create = DepartmentRoles::create([
                'department_id' => $request->department,
                'role_id' => $id
            ]);
        }

        if ($create)
            return response()->json(['status' => 1], 200);
        else
            return response()->json(['status' => 0], 200);

        return $request->tc_id;
    }


    public function listRoleDepartments(Request $request)
    {
        if ($request->tc_id != '') {
            $data = DB::table('view_department_roles_details')
                ->whereRaw('department_id =' . $request->tc_id)
                ->orderBy('updated_at', 'desc');
        } else {
            $data = DB::table('view_department_roles_details');
        }

        return DataTables::of($data)
            ->setRowId(function ($data) {
                return 'department-of-role-item-' . $data->id;
            })
            ->addColumn('delete', '<a href="javascript:void(0)" class="text-danger trash" id="{{$id}}" from="department-of-role">Kaldır</a>')
            ->addColumn('action', 'path.to.view')
            ->rawColumns(['delete', 'action'])
            ->make(true);
    }

    public function destroyRoleDepartment(Request $request)
    {
        $destroy = DepartmentRoles::find(intval($request->destroy_id))
            ->delete(['transshipment_center_code' => null]);

        if ($destroy) return 1;

        return 0;
    }

}
