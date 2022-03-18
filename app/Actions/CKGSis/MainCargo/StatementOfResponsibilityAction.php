<?php

namespace App\Actions\CKGSis\MainCargo;

use App\Models\Cargoes;
use App\Models\Currents;
use Lorisleiva\Actions\Concerns\AsAction;
use PhpOffice\PhpWord\TemplateProcessor;

class StatementOfResponsibilityAction
{
    use AsAction;

    public function handle($ctn)
    {
        $ctn = str_replace(' ', '', $ctn);

        $templateProccessor = new TemplateProcessor('backend/word-template/StatementOfResposibility.docx');

        $cargo = Cargoes::where('tracking_no', $ctn)->first();
        $sender = Currents::find($cargo->sender_id);

        $templateProccessor
            ->setValue('date', date('d / m / Y'));
        $templateProccessor
            ->setValue('name', $cargo->sender_name);
        $templateProccessor
            ->setValue('tckn', $sender->tckn);
        $templateProccessor
            ->setValue('phone', $cargo->sender_phone);
        $templateProccessor
            ->setValue('address', $cargo->sender_address);
        $templateProccessor
            ->setValue('ctn', TrackingNumberDesign($cargo->tracking_no));

        $fileName = 'ST-' . substr($cargo->sender_name, 0, 30) . '.docx';

        $templateProccessor
            ->saveAs($fileName);

        return response()
            ->download($fileName)
            ->deleteFileAfterSend(true);
    }
}
