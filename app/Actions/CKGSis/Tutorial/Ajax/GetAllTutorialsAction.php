<?php

namespace App\Actions\CKGSis\Tutorial\Ajax;

use App\Actions\CKGSis\Expedition\AjaxTransaction\FilterExpeditionAction;
use App\Actions\CKGSis\Expedition\AjaxTransaction\FilterExpeditionArrivalAction;
use App\Actions\CKGSis\Expedition\AjaxTransaction\GetExpeditionActions;
use App\Models\Tutorial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class GetAllTutorialsAction
{
    use AsAction;
    use AsController;

    public function asController(Request $request)
    {
        return $this->handle($request);
    }

    public function handle(Request $request)
    {


        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);

        $name = $request->name;
        $category = $request->category;
        $tutor = $request->tutor;
        $description = $request->description;
        $departureBranch = $request->departureBranch;
        $created_at = $request->created_at;


        $rows = Tutorial::query()
            ->when($name, function($q){ return $q->where('name', 'like', '%'.$name.'%');})
            ->get();



        return datatables()->of($rows)

            ->make(true);
    }


}
