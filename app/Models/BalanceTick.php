<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceTick extends Model
{
    /**
     * Attributes that are not mass assignable
     */
    protected $guarded = [];

    /**
     * Corresponding table name to model after
     */
    protected $table = 'trade_bot_balances';

    /**
     * Cast attributes to Carbon instances
     */
    protected $dates = [
        'time'
    ];

    /**
     * Remove updated/created columns
     */
    public $timestamps = false;

    /**
     * Get user that owns this bots profit tick
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get corresponding trading bot
     */
    public function tradeBot()
    {
        return $this->belongsTo(TradeBot::class, 'trade_bot_id');
    }
}
