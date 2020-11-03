<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
}
