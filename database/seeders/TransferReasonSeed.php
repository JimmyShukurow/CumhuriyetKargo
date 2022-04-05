<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferReasonSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            'Adres Sorunu Nedeniyle Alıcıya Ulaşılmadı',
            'Alıcı Adresinde Yok - Notlu Kargo',
            'Alıcı Kabul Etmedi (Ücret, Ürün Bedeli, Eksik İrsaliye-Fatura, Müşteri isteği Vb. Nedenlerden)',
            'Dağıtım Alanı Dışı',
            'Firma/İşyeri Kapalı-Teslim Alma Süresi Sınırlı',
            'Hasarlı Kargo',
            'İade Geldi',
        ];

        foreach ($reasons as $key)
            DB::table('transfer_reasons')
                ->insert([
                    'reason' => $key,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
    }
}
