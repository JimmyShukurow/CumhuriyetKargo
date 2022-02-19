<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgencyStatusAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
        #$dateFilter = $request->dateFilter;
        $dateFilter = 'true';

        if ($dateFilter == "true") {
            $diff = $firstDate->diffInDays($lastDate);
            if ($dateFilter) {
                if ($diff >= 30) {
                    return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 30 gün olabilir!'], 509);
                }
            }
        }
        $firstDate = substr($firstDate, 0, 10);
        $lastDate = substr($lastDate, 0, 10);


        $rows = DB::select('SELECT
	*
FROM
	view_agency_region
	INNER JOIN (
	SELECT
		departure_agency_code,
		count(*) AS total_bill_count,
		SUM( total_price ) AS endorsement,
		IFNULL(
			(
			SELECT
				cash_amount
			FROM
				(
				SELECT
					cargoes.departure_agency_code,
					SUM( total_price ) AS cash_amount
				FROM
					cargoes
					INNER JOIN cargo_collections ON cargo_collections.cargo_id = cargoes.id
				WHERE
					cargoes.deleted_at IS NULL
					AND collection_type_entered = "NAKİT"
				GROUP BY
					cargoes.departure_agency_code
				) AS begex
			WHERE
				begex.departure_agency_code = cargoes.departure_agency_code
			),
			0
		) AS cash_amount,
		IFNULL(
			(
			SELECT
				pos_amount
			FROM
				(
				SELECT
					cargoes.departure_agency_code,
					SUM( total_price ) AS pos_amount
				FROM
					cargoes
					INNER JOIN cargo_collections ON cargo_collections.cargo_id = cargoes.id
				WHERE
					cargoes.deleted_at IS NULL
					AND collection_type_entered = "POS"
				GROUP BY
					cargoes.departure_agency_code
				) AS begex
			WHERE
				begex.departure_agency_code = cargoes.departure_agency_code
			),
			0
		) AS pos_amount,
		IFNULL(
			(
			SELECT
				payment
			FROM
				( SELECT agency_id, SUM( payment ) AS payment FROM agency_payments GROUP BY agency_id ) AS begex
			WHERE
				begex.agency_id = cargoes.departure_agency_code
			),
			0
		) AS amount_deposited
	FROM
		cargoes
	WHERE
		deleted_at IS NULL
	GROUP BY
	departure_agency_code
	) AS s ON s.departure_agency_code = view_agency_region.id');


        return datatables()->of($rows)
            ->editColumn('endorsement', function ($key) {
                return round($key->endorsement, 2);
            })
            ->editColumn('cash_amount', function ($key) {
                return round($key->cash_amount, 2);
            })
            ->editColumn('pos_amount', function ($key) {
                return round($key->pos_amount, 2);
            })
            ->editColumn('debt', function ($key) {
                return round($key->endorsement - $key->amount_deposited - $key->pos_amount, 2);
            })
            ->make(true);
    }
}
