<?php

namespace App\Actions\CKGSis\Tutorial\Ajax;

use App\Models\Tutorial;
use Carbon\Carbon;
use Illuminate\Http\Request;
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


        $name = $request->videoName;
        $category = $request->category;
        $tutor = $request->tutor;
        $description = $request->description;
        $start_date = Carbon::createFromDate($request->start_date);
        $end_date = Carbon::createFromDate($request->end_date);

        $start_date = substr($start_date, 0, 10) . ' 00:00:00';
        $end_date = substr($end_date, 0, 10) . ' 23:59:59';


        $tutorials = Tutorial::query()
            ->when($name, function($q) use ($name) { return $q->where('name', 'like', '%'.$name.'%');})
            ->when($category, function($q) use ($category) { return $q->where('category', 'like', '%'.$category.'%');})
            ->when($tutor, function($q) use ($tutor) { return $q->where('tutor', 'like', '%'.$tutor.'%');})
            ->when($description, function($q) use ($description) { return $q->where('description', 'like', '%'.$description.'%');})
            ->when($start_date, function($q) use ($start_date, $end_date) { return $q->whereBetween('created_at', [$start_date, $end_date]);})

            ->get();


        return datatables()->of($tutorials)
        ->editColumn('link', function ($key) {
            return '<a href="' . $key->embedded_link . '" target="_blank">' . $key->embedded_link  . '</a>';
        })
        ->addColumn('edit', 'backend.tutorials.edit')

        ->rawColumns(['link' , 'edit'])

        ->make(true);

        
    }


}
