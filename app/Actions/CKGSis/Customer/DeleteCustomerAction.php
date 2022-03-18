<?php

namespace App\Actions\CKGSis\Customer;

use App\Models\Currents;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteCustomerAction
{
    use AsAction;

    public function handle($id)
    {
        $current = Currents::find($id);
        $creatorUser = User::find($current->created_by_user_id);

        $cargoesAsReciever = $current->cargoesAsReciever->count();
        $cargoesAsSender = $current->cargoesAsSender->count();

        if (Auth::user()->agency_code != $creatorUser->agency_code)
            return response()
                ->json(['status' => 0, 'message' => 'Şubenize ait bir müşteri olmadığından bu müşteriyi silemezsiniz!'], 403);

        else if ($cargoesAsReciever != 0 || $cargoesAsSender != 0)
            return response()
                ->json(['status' => 0, 'message' => 'Bu müşteriye daha önce fatura kesildiği için silme işlemini yapamazsınız!'], 403);

        else if (Carbon::parse($current->created_at)->diffInSeconds(Carbon::now()) < 86400 && $cargoesAsReciever == 0 && $cargoesAsSender == 0) {

            $current->delete();
            GeneralLog($current->current_code . ' Cari Kodlu müşteri silindi!');
            return response()->json(['status' => 1, 'message' => 'Bşarılı Silindi!'], 200);

        } else if (Carbon::parse($current->created_at)->diffInSeconds(Carbon::now()) > 86400)
            return response()
                ->json(['status' => 0, 'message' => '24 saat geçtigi için silemezsiniz!'], 403);
    }
}
