<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeBot extends Model
{
    /**
     * Attributes that are not mass assignable
     */
    protected $guarded = [];

    /**
     * Get bot ID with padded zeros
     */
    public function formattedId()
    {
        return str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get user that owns this bot
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get bot assigned strategy type
     */
    public function strategyType()
    {
        return $this->belongsTo(StrategyType::class, 'strategy_type_id');
    }

    /**
     * Get bot assigned interval
     */
    public function interval()
    {
        return $this->belongsTo(Interval::class, 'interval_id');
    }

    /**
     * Get bot assigned strategy
     */
    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'strategy_id');
    }

    /**
     * Get bot assigned strategy indicators
     */
    public function indicators()
    {
        return $this->hasMany(TradeBotIndicator::class, 'trade_bot_id')->with(['indicator']);
    }

    /**
     * Get bot assigned buy indicators
     */
    public function buyIndicators()
    {
        return $this->hasMany(TradeBotIndicator::class, 'trade_bot_id')->where('signal_type', 'buy')->with(['indicator']);
    }

    /**
     * Get bot assigned sell indicators
     */
    public function sellIndicators()
    {
        return $this->hasMany(TradeBotIndicator::class, 'trade_bot_id')->where('signal_type', 'sell')->with(['indicator']);
    }

    /**
     * Get bot state instance
     */
    public function state()
    {
        return $this->belongsTo(TradeBotState::class, 'state_id');
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

    /**
     * Get corresponding buy ordering type
     */
    public function buyType()
    {
        return $this->belongsTo(OrderType::class, 'buy_type_id');
    }

    /**
     * Get corresponding sell ordering type
     */
    public function sellType()
    {
        return $this->belongsTo(OrderType::class, 'sell_type_id');
    }

    /**
     * Get corresponding buy order length
     */
    public function buyLength()
    {
        return $this->belongsTo(OrderLength::class, 'buy_length_id');
    }

    /**
     * Get corresponding sell order length
     */
    public function sellLength()
    {
        return $this->belongsTo(OrderLength::class, 'sell_length_id');
    }

    /**
     * Get trades made by this bot
     */
    public function trades()
    {
        return $this->hasMany(Trade::class, 'trade_bot_id')->with(['buyOrder', 'sellOrder']);
    }

    /**
     * Get bot balance ticks
     */
    public function balanceTicks()
    {
        return $this->hasMany(BalanceTick::class, 'trade_bot_id');
    }

    /**
     * Get bot notifications
     */
    public function notifications()
    {
        return $this->hasMany(TradeBotNotification::class, 'trade_bot_id');
    }

    /**
     * Calculates percentage change from amount invested
     */
    public function percentageChange()
    {
        return (($this->trade_current_balance - $this->trade_start_balance) / $this->trade_start_balance) * 100;
    }
}
