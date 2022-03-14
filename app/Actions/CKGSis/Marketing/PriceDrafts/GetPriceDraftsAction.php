<?php

namespace App\Actions\CKGSis\Marketing\PriceDrafts;

use App\Models\Districts;
use App\Models\PriceDrafts;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPriceDraftsAction
{
    use AsAction;

    public function handle()
    {
        $rows = PriceDrafts::with('user.role')->get();

        return datatables()->of($rows)
            ->editColumn('created_at', function ($key) {
                return $key->created_at;
            })
            ->editColumn('updated_at', function ($key) {
                return $key->updated_at;
            })
            ->editColumn('name_surname', function ($key) {
                return $key->user->name_surname . '(' . $key->user->role->display_name . ')';
            })
            ->addColumn('edit', function ($key) {
                return '<button class="btn btn-sm btn-primary detail-price-draft" id="' . $key->id . '">Düzenle</button>';
            })
            ->addColumn('delete', function ($key) {
                return '<button class="btn btn-sm btn-danger trash" from="price-draft" id="' . $key->id . '">Sil</button>';
            })
            ->addColumn('agency_permission', function ($key) {
                return $key->agency_permission == '1' ? '<b class="text-success">Evet</b>' : '<b class="text-danger">Hayır</b>';
            })
            ->rawColumns(['edit', 'delete', 'agency_permission'])
            ->make(true);
    }
}
