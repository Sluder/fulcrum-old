<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\BalanceTick;
use App\Models\Strategy;
use App\Models\TradeBot;
use App\Models\TradeBotIndicator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeBotController extends Controller
{
    /**
     * Bots view
     */
    public function view()
    {
        $bots = TradeBot::with([
            'strategy',
            'state',
            'buyType',
            'buyLength',
            'sellType',
            'sellLength',
            'interval',
            'strategyType',
            'indicators',
            'trades' => function($query) {
                return $query->limit(10);
            }
        ])->get();

        foreach ($bots as $bot) {
            $bot->dollar_change = $bot->trade_current_balance - $bot->trade_start_balance;
            $bot->percent_change = $bot->percentageChange();
        }

        return view('bots', compact('bots'));
    }

    /**
     * View form for creating/updating trading bots
     */
    public function form(TradeBot $bot = null)
    {
        if ($bot) {
            $bot->load([
                'strategy',
                'state',
                'buyType',
                'buyLength',
                'sellType',
                'sellLength',
                'interval',
                'strategyType',
                'indicators'
            ]);
        }

        $strategies = Strategy::all();
        $indicators = Indicator::all();

        return view('bot-form', compact('bot', 'strategies', 'indicators'));
    }

    /**
     * Creates new trading bot in database
     */
    public function store(Request $request)
    {
        $bot = TradeBot::create([
            'user_id' => Auth::id(),
            'strategy_type_id' => $request['strategy-type'],
            'interval_id' => $request['interval'],
            'strategy_id' => $request['strategy'],
            'stock_ticker' => $request['ticker'],
            'initial_balance' => $request['balance'],
            'trade_start_balance' => $request['balance'],
            'trade_current_balance' => $request['balance'],
            'state_id' => 1,
            'buy_type_id' => $request['buy-order-type'],
            'sell_type_id' => $request['sell-order-type'],
            'buy_length_id' => $request['buy-order-length'],
            'sell_length_id' => $request['sell-order-length'],
            'last_ran' => Carbon::now(),
            'should_delete' => false,
            'is_paused' => false
        ]);

        // Save indicators for trading bot
        if (intval($request['strategy-type']) === 1) {
            foreach ($request['buy-indicators'] as $indicator => $buy_indicator) {
                TradeBotIndicator::create([
                    'trade_bot_id' => $bot->id,
                    'indicator_id' => Indicator::where('key', $indicator)->first()->id,
                    'signal_type' => 'buy',
                    'data' => $buy_indicator
                ]);
            }

            foreach ($request['sell-indicators'] as $indicator => $sell_indicator) {
                TradeBotIndicator::create([
                    'trade_bot_id' => $bot->id,
                    'indicator_id' => Indicator::where('key', $indicator)->first()->id,
                    'signal_type' => 'sell',
                    'data' => $sell_indicator
                ]);
            }
        }

        return redirect()->route('bots.view')->with([
            "success" => "Successfully created trading bot {$bot->formattedId()} for {$request['ticker']}"
        ]);
    }

    /**
     * Update an existing trading bot
     */
    public function update(TradeBot $bot, Request $request)
    {
        $bot->indicators()->delete();

        $bot->update([
            'stock_ticker' => $request['ticker'],
            'interval_id' => $request['interval'],
            'strategy_id' => $request['strategy'],
            'buy_length_id' => $request['buy-order-length'],
            'sell_length_id' => $request['sell-order-length'],
            'buy_type_id' => $request['buy-order-type'],
            'sell_type_id' => $request['sell-order-type']
        ]);

        // Save indicators for trading bot
        if (intval($request['strategy-type']) === 1) {
            foreach ($request['buy-indicators'] as $indicator => $buy_indicator) {
                TradeBotIndicator::create([
                    'trade_bot_id' => $bot->id,
                    'indicator_id' => Indicator::where('key', $indicator)->first()->id,
                    'signal_type' => 'buy',
                    'data' => $buy_indicator
                ]);
            }

            foreach ($request['sell-indicators'] as $indicator => $sell_indicator) {
                TradeBotIndicator::create([
                    'trade_bot_id' => $bot->id,
                    'indicator_id' => Indicator::where('key', $indicator)->first()->id,
                    'signal_type' => 'sell',
                    'data' => $sell_indicator
                ]);
            }
        }

        return redirect()->route('bots.view')->with([
            "success" => "Successfully updated trading bot {$bot->formattedId()}"
        ]);
    }

    /**
     * Deletes trading bot if in buy status, otherwise delete in future
     */
    public function delete(TradeBot $bot)
    {
        switch ($bot->state->value) {
            case 0:
                $message = "Bot {$bot->formattedId()} will be deleted on its next run";
                break;
            case 1:
                $message = "A sell order will be placed for trading bot {$bot->formattedId()} after its pending buy order is closed, then will self-delete";
                break;
            case 2:
                $message = "A sell order is pending for trading bot {$bot->formattedId()}, and will self-delete after order is closed";
                break;
            case 3:
                $message = "Trading bot {$bot->formattedId()} will be deleted once its current sell order is closed";
                break;
        }

        if ($bot) {
            $bot->update([
                'should_delete' => true
            ]);
        }

        return redirect()->route('bots.view')->with([
            "success" => $message
        ]);
    }

    /**
     * AJAX: Get balance ticks for a trading bot
     */
    public function getBalanceTicks(TradeBot $bot, Request $request)
    {
        switch ($request['interval']) {
            case 'D':
                $limit = 24; break;
            case 'M':
                $limit = 30; break;
            default:
                $limit = 30;
        }

        $ticks = BalanceTick::where([
            'user_id' => Auth::id(),
            'trade_bot_id' => $bot->id,
            'interval' => $request['interval']
        ])->limit($limit)->get();

        return json_encode($ticks);
    }

    /**
     * View for bot logging
     */
    public function logs(TradeBot $bot)
    {
        $logs = $bot->notifications()->orderBy('created_on', 'desc')->simplePaginate(10);

        return view('bot-logs', compact('bot', 'logs'));
    }

    /**
     * Pause a bot from making orders
     */
    public function pause(TradeBot $bot)
    {
        $bot->update([
            'is_paused' => true
        ]);

        return redirect()->route('bots.view')->with([
            "success" => "Successfully paused bot {$bot->formattedId()}. This bot will not make any trades until un-paused"
        ]);
    }

    /**
     * Un-pause a bot so it can trade again
     */
    public function unPause(TradeBot $bot)
    {
        $bot->update([
            'is_paused' => false
        ]);

        return redirect()->route('bots.view')->with([
            "success" => "Successfully un-paused trade bot {$bot->formattedId()}. This bot will start trading again"
        ]);
    }
}
