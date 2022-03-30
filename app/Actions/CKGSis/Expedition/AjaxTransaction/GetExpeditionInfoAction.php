<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use Lorisleiva\Actions\Concerns\AsAction;

class GetExpeditionInfoAction
{
    use AsAction;

    public function handle($id)
    {
        $expedition = Expedition::with(
            [
                'car' => function($q) {$q->withTrashed();},
                'user',
                'movements.user.role',
                'routes.branch',
                'cargoes' => function($q){$q->with(['cargo' => function($q){$q->withTrashed();}, 'user'=>function($q){$q->with('role');}])->groupBy('cargo_id');},
                'seals'
            ]
        )->where('id',$id)->first();

        $departure_branch = $expedition->routes->where('route_type', '1')->first()->branch_type == 'Acente' ?
            $expedition->routes->where('route_type', '1')->first()->branch->agency_name . ' ŞUBE' :
            $expedition->routes->where('route_type', '1')->first()->branch->tc_name . ' TRM.';

        $between_branchs = $expedition->routes()->where('route_type',0)->orderBy('order')->get();
        $between_branchs = $between_branchs->map(function ($item){
            if ($item->branch_type == 'Acente') {
                return $item->branch->agency_name . ' ŞUBE';
            }
            if ($item->branch_type == 'Aktarma') {
                return $item->branch->tc_name . ' TRM.';
            }
        });



        $allCargoes = $expedition->allCargoes()->with(
            [
                'cargo' => function($q){$q->withTrashed();},
                'user'=>function($q){$q->with('role')->withTrashed();},
                'unloadedUser'=>function($q){$q->with('role')->withTrashed();},
                'deletedUser' =>function($q){$q->with('role')->withTrashed();},
            ]
        )->get();


        $arrival_branch = $expedition->routes->where('route_type', '-1')->first()->branch_type == 'Acente' ?
            $expedition->routes->where('route_type', '-1')->first()->branch->agency_name . ' ŞUBE' :
            $expedition->routes->where('route_type', '-1')->first()->branch->tc_name . ' TRM.';


        $data = getUserBranchInfoWithUserID($expedition->user_id);
        $expedition->branch = $data['name'] . ' ' . $data['type'];
        $expedition->departure_branch = $departure_branch;
        $expedition->arrival_branch = $arrival_branch;
        $expedition->betweens = $between_branchs;
        $expedition->allCargoes = $allCargoes;



        return $expedition;
    }
}
