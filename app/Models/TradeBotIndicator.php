<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeBotIndicator extends Model
{
    /**
     * Attributes that are not mass assignable
     */
    protected $guarded = [];

    /**
     * Remove updated/created columns
     */
    public $timestamps = false;

    /**
     * Get corresponding trading bot
     */
    public function tradeBot()
    {
        return $this->belongsTo(TradeBot::class, 'trade_bot_id');
    }

    /**
     * Get indicator this represents
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }
}
