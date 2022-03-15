<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use Lorisleiva\Actions\Concerns\AsAction;

class GetExpeditionActions
{
    use AsAction;

    public function handle( $ids, $firstDate, $lastDate, $doneStatus = null, $serialNo = null, $plaka = null, $creator = null,)
    {
        $rows = Expedition::with(
            [
                'car:id,plaka',
                'user:users.id,name_surname,display_name',
                'routes.branch',
                'cargoes'
            ])
            ->when($doneStatus != null, function ($q) use ($doneStatus) {
                return $q->where('done', $doneStatus);
            })
            ->when($serialNo, function ($q) use ($serialNo) {
                return $q->where('serial_no', str_replace(' ', '', $serialNo));
            })
            ->when($plaka, function ($q) use ($plaka) {
                return $q->whereHas('car', function ($query) use ($plaka) {
                    $query->where('plaka', 'like', '%' . $plaka . '%');
                });
            })
            ->when($creator, function ($q) use ($creator) {
                return $q->whereHas('user', function ($query) use ($creator) {
                    $query->where('name_surname', 'like', '%' . $creator . '%');
                });
            })
            ->whereIn('user_id', $ids)
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->get();

        return $rows;
    }
}
