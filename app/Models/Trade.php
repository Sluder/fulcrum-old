<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    /**
     * Attributes that are not mass assignable
     */
    protected $guarded = [];

    /**
     * Get corresponding trading bot
     */
    public function tradeBot()
    {
        return $this->belongsTo(TradeBot::class, 'trade_bot_id');
    }

    /**
     * Get corresponding buy order
     */
    public function buyOrder()
    {
        return $this->belongsTo(Order::class, 'buy_order_id');
    }

    /**
     * Get corresponding sell order
     */
    public function sellOrder()
    {
        return $this->belongsTo(Order::class, 'sell_order_id');
    }
}
