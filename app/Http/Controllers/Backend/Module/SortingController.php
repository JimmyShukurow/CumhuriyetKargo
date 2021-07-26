<?php

namespace App\Http\Controllers\Backend\Module;

use App\Http\Controllers\Controller;
use App\Models\ModuleGroups;
use App\Models\Modules;
use App\Models\Roles;
use App\Models\SubModules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SortingController extends Controller
{
    public function index()
    {

        $tab = 'Roles';
        $data['sub_modules'] = DB::table('sub_modules')
            ->select('sub_modules.id', 'sub_modules.sub_name', 'sub_modules.must', 'sub_modules.link', 'sub_modules.description', 'modules.name', 'modules.ico', 'modules.must as module_must')
            ->join('modules', 'sub_modules.module_id', '=', 'modules.id')
            ->orderBy('module_must')
            ->get();
        $data['module_groups'] = ModuleGroups::all()->sortBy('must');

        return view('backend.module.sorting.index', compact(['data', 'tab']));
    }

    public function moduleGroupSorting()
    {
        print_r($_POST['item']);
        foreach ($_POST['item'] as $key => $value) {
            $mg = ModuleGroups::find(intval($value));
            $mg->must = intval($key);
            $mg->save();
        }
        echo true;
    }

    public function moduleSorting()
    {
        print_r($_POST['item']);
        foreach ($_POST['item'] as $key => $value) {
            $mg = Modules::find(intval($value));
            $mg->must = intval($key);
            $mg->save();
        }
        echo true;
    }

    public function subModuleSorting()
    {
        print_r($_POST['item']);
        foreach ($_POST['item'] as $key => $value) {
            $mg = SubModules::find(intval($value));
            $mg->must = intval($key);
            $mg->save();
        }
        echo true;
    }
}
