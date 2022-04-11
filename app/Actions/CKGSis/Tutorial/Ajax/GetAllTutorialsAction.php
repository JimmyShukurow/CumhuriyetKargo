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
        $created_at = $request->created_at;


        $rows = Tutorial::query()
            ->when($name, function($q) use ($name) { return $q->where('name', 'like', '%'.$name.'%');})
            ->when($category, function($q) use ($category) { return $q->where('category', 'like', '%'.$category.'%');})
            ->when($tutor, function($q) use ($tutor) { return $q->where('tutor', 'like', '%'.$tutor.'%');})
            ->when($description, function($q) use ($description) { return $q->where('description', 'like', '%'.$description.'%');})
            ->when($created_at, function($q) use ($created_at) { return $q->where('name', $created_at);})

            ->get();



        return datatables()->of($rows)

            ->make(true);
    }


}
