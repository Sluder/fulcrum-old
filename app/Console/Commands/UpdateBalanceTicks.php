<?php

namespace App\Console\Commands;

use App\Models\BalanceTick;
use App\Models\TradeBot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBalanceTicks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:balance-ticks {interval}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates balance ticks for a trading bot';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $trade_bots = TradeBot::with(['user'])->get();

        foreach ($trade_bots as $trade_bot) {
            BalanceTick::create([
                'user_id' => $trade_bot->user->id,
                'trade_bot_id' => $trade_bot->id,
                'interval' => $this->argument('interval'),
                'balance' => $trade_bot->trade_start_balance,
                'time' => Carbon::now()
            ]);
        }
    }
}
