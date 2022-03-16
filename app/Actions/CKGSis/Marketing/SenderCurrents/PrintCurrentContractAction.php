<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents;

use App\Models\Agencies;
use App\Models\CurrentPrices;
use App\Models\Currents;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use PhpOffice\PhpWord\TemplateProcessor;

class PrintCurrentContractAction
{
    use AsAction;

    public function handle($CurrentCode)
    {
        $CurrentCode = str_replace(' ', '', $CurrentCode);
        $templateProccessor = new TemplateProcessor('backend/word-template/CurrentContract.docx');

        $current = Currents::where('current_code', $CurrentCode)->first();
        $currentPrice = CurrentPrices::where('current_code', $CurrentCode)->first();
        $agency = Agencies::find($current->agency);

        $templateProccessor->setValue('date', date('d/m/Y'));
        $templateProccessor->setValue('name', $current->name);

        $templateProccessor->setValue('file', $currentPrice->file_price);
        $templateProccessor->setValue('mi', $currentPrice->mi_price);
        $templateProccessor->setValue('d1_5', $currentPrice->d_1_5);
        $templateProccessor->setValue('d6_10', $currentPrice->d_6_10);
        $templateProccessor->setValue('d11_15', $currentPrice->d_11_15);
        $templateProccessor->setValue('d16_20', $currentPrice->d_16_20);
        $templateProccessor->setValue('d21_25', $currentPrice->d_21_25);
        $templateProccessor->setValue('d26_30', $currentPrice->d_26_30);
        $templateProccessor->setValue('d31_35', $currentPrice->d_31_35);
        $templateProccessor->setValue('d36_40', $currentPrice->d_36_40);
        $templateProccessor->setValue('d41_45', $currentPrice->d_41_45);
        $templateProccessor->setValue('d46_50', $currentPrice->d_46_50);
        $templateProccessor->setValue('amount_of_increase', $currentPrice->amount_of_increase);
        $templateProccessor->setValue('CurrentCode', CurrentCodeDesign($current->current_code));
        $templateProccessor->setValue('category', $current->category);
        $templateProccessor->setValue('tax_office', $current->tax_administration);
        $templateProccessor->setValue('vkn', $current->tckn);
        $templateProccessor->setValue('agency', $agency->city . '/' . $agency->district . " - " . $agency->agency_name . " (" . $agency->agency_code . ")");
        $templateProccessor->setValue('contract_start_date', Carbon::parse($current->contract_start_date)->format('d/m/Y'));
        $templateProccessor->setValue('contract_end_date', Carbon::parse($current->contract_end_date)->format('d/m/Y'));
        $templateProccessor->setValue('contract_lifetime', Carbon::parse($current->contract_start_date)->diffInDays($current->contract_end_date));

        $fileName = 'CS-' . substr($current->name, 0, 30) . '.docx';
        $pdfName = 'CS-' . substr($current->name, 0, 30) . '.pdf';


        $templateProccessor
            ->saveAs($fileName);

        return response()
            ->download($fileName)
            ->deleteFileAfterSend(true);
    }
}
