<?php

namespace App\Actions\CKGSis\Layout;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserModuleAndSubModuleAction
{
    use AsAction;

    public function handle()
    {
        $user = User::with('role.modules.subModule.module.moduleGroup')
            ->where('id', Auth::id())
            ->first();
        $user = collect($user);

        $modules = collect();
        for ($i = 0; $i < count($user['role']['modules']); $i++) {
            if ($user['role']['modules'][$i]['sub_module'] != null)
                $modules->push([
                    'module_group_id' => $user['role']['modules'][$i]['sub_module']['module']['module_group']['id'],
                    'module_group_name' => $user['role']['modules'][$i]['sub_module']['module']['module_group']['title'],
                    'module_group_must' => $user['role']['modules'][$i]['sub_module']['module']['module_group']['must'],
                    'module_id' => $user['role']['modules'][$i]['sub_module']['module']['id'],
                    'module_name' => $user['role']['modules'][$i]['sub_module']['module']['name'],
                    'module_must' => $user['role']['modules'][$i]['sub_module']['module']['must'],
                    'module_ico' => $user['role']['modules'][$i]['sub_module']['module']['ico'],
                    'sub_module_id' => $user['role']['modules'][$i]['sub_module']['id'],
                    'sub_module_name' => $user['role']['modules'][$i]['sub_module']['sub_name'],
                    'sub_module_must' => $user['role']['modules'][$i]['sub_module']['must'],
                    'sub_module_link' => $user['role']['modules'][$i]['sub_module']['link'],
                ]);
        }

        $modules = $modules->sortBy([
            fn($a, $b) => $a['module_group_must'] <=> $b['module_group_must'],
            fn($a, $b) => $a['module_must'] <=> $b['module_must'],
            fn($a, $b) => $a['sub_module_must'] <=> $b['sub_module_must'],
        ]);

        return $modules;
    }
}
