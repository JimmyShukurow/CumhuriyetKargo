<?php

namespace App\Console\Commands;

use App\Models\TicketDetails;
use App\Models\Tickets;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CronForTicketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $getAuthIDs = User::where('agency_code', 1)->pluck('id');
        $expDate = Carbon::now()->subHour(24);
        $tickets = Tickets::with(['lastReply' => function ($query) use ($getAuthIDs) {
            return $query->whereNotIn('user_id', $getAuthIDs);
        }])
            ->whereDate('updated_at', '<', $expDate)
            ->whereIn('status', ['AÇIK', 'BEKLEMEDE', 'CEVAPLANDI'])
            ->get();


//        foreach ($tickets as $key) {
//
//            $update = Tickets::find($key->id)
//                ->update([
//                    'status' => 'KAPANDI',
//                ]);
//
//            $insert = TicketDetails::create([
//                'ticket_id' => $key->id,
//                'user_id' => 0,
//                'message' => '#### ==> Status Updated <== #### to:Kapandı',
//                'file1' => '',
//                'file2' => '',
//                'file3' => '',
//                'file4' => '',
//            ]);
//
//            activity()
//                ->causedBy(0)
//                ->performedOn($key)
//                ->inLog('Ticket Updated')
//                ->log('Destek talebine 24 saat içerisinde yanıt gelmediğinden Sistem tarafından, durumu KAPANDI olarak güncellendi.');
//        }
        info('cron for ticket worked!');
    }
}
