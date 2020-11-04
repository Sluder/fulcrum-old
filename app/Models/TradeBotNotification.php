<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeBotNotification extends Model
{
    /**
     * Attributes that are not mass assignable
     */
    protected $guarded = [];

    /**
     * Cast attributes to Carbon instances
     */
    protected $dates = [
        'user_notified_on',
        'created_on'
    ];

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
}
