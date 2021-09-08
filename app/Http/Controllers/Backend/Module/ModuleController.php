<?php

namespace App\Http\Controllers\Backend\Module;

use App\Http\Controllers\Controller;
use App\Models\ModuleGroups;
use App\Models\Modules;
use App\Models\RoleModules;
use App\Models\Roles;
use App\Models\SubModules;
use App\Models\SystemUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\AssignOp\Mod;
use Spatie\Activitylog\Traits\LogsActivity;

class ModuleController extends Controller
{
    public function index()
    {
        $tab = 'Roles';
        $data['roles'] = Roles::all();
        $data['sub_modules'] = DB::table('sub_modules')
            ->select('sub_modules.id', 'sub_modules.sub_name', 'sub_modules.must', 'sub_modules.link', 'sub_modules.description', 'modules.name', 'modules.ico', 'modules.must as module_must')
            ->join('modules', 'sub_modules.module_id', '=', 'modules.id')
            ->orderBy('module_must')
            ->get();

        $data['module_groups'] = ModuleGroups::all();

        $data['modules'] = DB::table('modules')
            ->select('modules.id as id', 'name', 'ico', 'module_group_id', 'modules.must', 'modules.description', 'module_groups.title', 'module_groups.must as mg_must', 'modules.created_at', 'modules.updated_at')
            ->join('module_groups', 'modules.module_group_id', '=', 'module_groups.id')
            ->orderBy('mg_must')
            ->get();

        return view('backend.module.index', compact(['data', 'tab']));
    }

    public function addRole()
    {
        return view('backend.module.role.create');
    }

    public function insertRole(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required'
        ]);

        $insert = Roles::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        if ($insert) {
            return back()->with('success', 'İşlem Başarılı, Rol Eklendi!');
        }
        return back()->with('error', 'İşlem Başarısız, Lütfen Daha Sonra Tekrar Deneyin!');


        return $request->all();
    }

    public function detroyRole(Request $request)
    {
        $destroy = Roles::find(intval($request->destroy_id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function editRole($id)
    {
        $role = Roles::where('id', $id)->first();

        if ($role === null) {
            return redirect(route('module.Index'))->with('error', 'Düzenlemek istediğiniz yetki bulunamadı!');
        } else {
            return view('backend.module.role.edit', compact('role'));
        }
    }


    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required'
        ]);

        $update = Roles::find($id)->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        if ($update) {
            return back()->with('success', 'İşlem Başarılı, Rol Güncellendi!');
        }
        return back()->with('error', 'İşlem Başarısız, Lütfen Daha Sonra Tekrar Deneyin!');

        return $request->all();
    }


    public function addModuleGroup()
    {
        return view('backend.module.module_groups.create');
    }

    public function insertModuleGroup(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'must' => 'numeric',
            'description' => 'required'
        ]);

        $insert = ModuleGroups::create([
            'title' => tr_strtoupper($request->title),
            'must' => $request->must,
            'description' => $request->description,
        ]);


        if ($insert) {
            return back()->with('success', 'İşlem Başarılı, Modül Grubu Eklendi!');
        }
        return back()->with('error', 'İşlem Başarısız, Lütfen Daha Sonra Tekrar Deneyin!');

        return $request->all();
    }

    public function editModuleGroup($id)
    {
        $mg = ModuleGroups::where('id', $id)->first();

        if ($mg === null) {
            return redirect(route('module.Index'))->with('error', 'Düzenlemek istediğiniz yetki bulunamadı!');
        } else {
            return view('backend.module.module_groups.edit', compact('mg'));
        }
    }

    public function updateModuleGroup(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
            'must' => 'numeric',
            'description' => 'required'
        ]);

        $update = ModuleGroups::find($id)
            ->update([
                'title' => tr_strtoupper($request->title),
                'must' => $request->must,
                'description' => $request->description,
            ]);

        if ($update) {
            return back()->with('success', 'İşlem Başarılı, Modül Grubu Güncellendi!');
        }
        return back()->with('error', 'İşlem Başarısız, Lütfen Daha Sonra Tekrar Deneyin!');


        return $request->all();
    }


    public function detroyModuleGroup(Request $request)
    {
        $destroy = ModuleGroups::find(intval($request->destroy_id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }


    public function addModule()
    {
        $data = ModuleGroups::get();
        return view('backend.module.modules.create', compact('data'));
    }

    public function insertModule(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ico' => 'required',
            'module_group_id' => 'required|numeric',
            'must' => 'nullable|numeric'
        ]);

        $exists = ModuleGroups::find($request->module_group_id)->first();
        if ($exists === null) {
            $request->flash();
            return back()->with('error', 'Geçerli bir modül grubu seçiniz!');
        }

        $insert = Modules::create([
            'name' => $request->name,
            'ico' => $request->ico,
            'module_group_id' => $request->module_group_id,
            'must' => $request->must,
            'description' => $request->description
        ]);

        if ($insert) return back()->with('success', 'İşlem Başarılı, Modül Eklendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function editModule($id)
    {
        $data = ModuleGroups::get();
        $module = Modules::where('id', $id)->first();

        if ($module === null) {
            return redirect(route('module.Index'))->with('error', 'Düzenlemek istediğiniz modül bulunamadı!');
        } else {
            return view('backend.module.modules.edit', compact(['module', 'data']));
        }
    }

    public function updateModule(Request $request, $id)
    {

        $module = Modules::where('id', $id)->first();
        if ($module === null) return redirect(route('module.Index'))->with('error', 'Düzenlemek istediğiniz modül bulunamadı!');

        $request->validate([
            'name' => 'required',
            'ico' => 'required',
            'module_group_id' => 'required|numeric',
            'must' => 'nullable|numeric'
        ]);

        $exists = ModuleGroups::find($request->module_group_id)->first();
        if ($exists === null) {
            $request->flash();
            return back()->with('error', 'Geçerli bir modül grubu seçiniz!');
        }

        $insert = Modules::find($id)
            ->update([
                'name' => $request->name,
                'ico' => $request->ico,
                'module_group_id' => $request->module_group_id,
                'must' => $request->must,
                'description' => $request->description
            ]);


        ## Example Logging Bitch!
        activity()
            ->inLog('General System Users Log')
            ->causedBy(Auth::user()->id)
            ->withProperties(['CustomProperty' => 'customValue'])
            ->log('Yo what up bitch?? this is a update transcation bitch bitvh !!!!');

        if ($insert) return back()->with('success', 'İşlem Başarılı, Modül Düzenlendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function detroyModule(Request $request)
    {
        $destroy = Modules::find(intval($request->destroy_id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function addSubModule()
    {
        $data = Modules::get();
        return view('backend.module.sub_modules.create', compact('data'));
    }

    public function insertSubModule(Request $request)
    {
        $request->validate([
            'sub_name' => 'required',
            'link' => 'required',
            'module_id' => 'required|numeric',
            'must' => 'nullable|numeric'
        ]);

        $exists = Modules::where('id', $request->module_id)->first();
        if ($exists === null) {
            $request->flash();
            return back()->with('error', 'Geçerli bir modül seçiniz!');
        }

        $insert = SubModules::create([
            'sub_name' => $request->sub_name,
            'link' => $request->link,
            'module_id' => $request->module_id,
            'must' => $request->must,
            'description' => $request->description
        ]);

        if ($insert) return back()->with('success', 'İşlem Başarılı, Alt Modül Eklendi!');
        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function editSubModule($id)
    {
        $data = Modules::get();
        $sm = SubModules::where('id', $id)->first();

        if ($sm === null) {
            return redirect(route('module.Index'))->with('error', 'Düzenlemek istediğiniz alt modül bulunamadı!');
        } else {
            return view('backend.module.sub_modules.edit', compact(['sm', 'data']));
        }
    }

    public function updateSubModule(Request $request, $id)
    {
        $request->validate([
            'sub_name' => 'required',
            'link' => 'required',
            'module_id' => 'required|numeric',
            'must' => 'nullable|numeric'
        ]);

        $exists = Modules::where('id', $request->module_id)->first();
        if ($exists === null) {
            $request->flash();
            return back()->with('error', 'Geçerli bir modül seçiniz!');
        }

        $insert = SubModules::where('id', $id)
            ->update([
                'sub_name' => $request->sub_name,
                'link' => $request->link,
                'module_id' => $request->module_id,
                'must' => $request->must,
                'description' => $request->description
            ]);

        if ($insert) return back()->with('success', 'İşlem Başarılı, Alt Modül Güncellendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function detroySubModule(Request $request)
    {
        $destroy = SubModules::find(intval($request->destroy_id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function getSubModuleOfRole(Request $request)
    {
        $permissions = DB::table('role_permissions')
            ->where('role_id', $request->role)
            ->orderBy('module_group_must')
            ->orderBy('module_must')
            ->get();

        return response()->json($permissions, 200);
    }

    public function getNonPermissionsOfRole(Request $request)
    {
        $permissions = DB::select('call GetRolePermissionsForRole(' . $request->role . ')');
        return response()->json($permissions, 200);
    }

    public function insertSubModuleToRole(Request $request)
    {
        $insert = RoleModules::create([
            'role_id' => intval($request->role_id),
            'sub_module_id' => intval($request->module_id)
        ]);

        if ($insert) return 1;

        return 0;
    }

    public function destroySubModuleOfRole(Request $request)
    {
        $destroy = RoleModules::find(intval($request->destroy_id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function systemUpdateIndex()
    {
        $data = SystemUpdate::orderBy('created_at', 'desc')->paginate(10); // paginate gelecek
        GeneralLog('[Admin] Sistem güncellemeleri sayfası görüntülendi.');
        return view('backend.module.system_update.admin.index', compact('data'));
    }

    public function systemUpdateCreate()
    {
        $version = getSystemVersion();
        return view('backend.module.system_update.admin.create', compact('version'));
    }

    public function systemUpdateEdit($id)
    {
        $data = SystemUpdate::find($id);

        if ($data === null)
            return redirect(route('module.systemUpdate.Index'))->with('error', 'Kayıt bulunamadı!');

        GeneralLog($id . ' id\'li [Admin] Sistem güncellemele düzenleme sayfası görüntülendi.');
        return view('backend.module.system_update.admin.edit', compact('data'));
    }

    public function systemUpdateUpdate(Request $request)
    {
        $request->validate([
            'version' => 'required',
            'title' => 'required|max:200|',
            'content' => 'required|min:3'
        ]);

        $system = SystemUpdate::find($request->id);
        $system->version = $request->version;
        $system->title = $request->title;
        $system->content = $request->content;
        $system->save();

        if ($system) {
            return back()->with('success', 'İşlem Başarılı, Güncellendi');
        } else {
            return back()->with('warning', 'İşlem Hatalı, Bir Aksilik Var');
        }
    }

    public function systemUpdateDelete($id)
    {
        $data = SystemUpdate::find($id);
        if ($data === null)
            return redirect(route('module.systemUpdate.Index'))->with('error', 'Kayıt bulunamadı!');

        $delete = SystemUpdate::find($id)->delete();

        if ($delete)
            return redirect(route('module.systemUpdate.Index'))
                ->with('success', 'İşlem Başarılı, Güncelleme Silindi!');
        else
            return back()->with('error', 'İşlem Hatalı, Bir Aksilik Var');

    }

    public function systemUpdateInsert(Request $request)
    {
        $request->validate([
            'version' => 'required',
            'title' => 'required|max:200|',
            'content' => 'required|min:3'
        ]);

        $system = new SystemUpdate();
        $system->version = $request->version;
        $system->title = $request->title;
        $system->content = $request->content;
        $system->save();

        if ($system) {
            return back()->with('success', 'İşlem Başarılı, Sistem Güncellendi');
        } else {
            return back()->with('warning', 'İşlem Hatalı, Bir Aksilik Var');
        }
    }

    public function systemUpdateView()
    {
        $data = SystemUpdate::orderBy('created_at', 'desc')->paginate(10); // paginate gelecek
        GeneralLog('Sistem güncellemeleri sayfası görüntülendi.');
        return view('backend.module.system_update.view', compact('data'));
    }

    public function systemUpdateShow($id)
    {
        return SystemUpdate::findOrFail($id);
    }

}
