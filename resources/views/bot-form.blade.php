@extends('layouts.app')

@section('title', 'Form')

@section('content')
    <div class="container-fluid bot-form">
        <form action="{{ isset($bot) ? route('bots.update', ['bot' => $bot->id]) : route('bots.store') }}" method="POST">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-header">
                            <i class="fas fa-{{ isset($bot) ? 'edit' : 'plus' }} color-accent header-icon"></i> {{ isset($bot) ? 'Update' : 'Create' }} Trading Bot
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ticker">Ticker</label>
                                    <input type="text" name="ticker" class="form-control" value="{{ isset($bot) ? $bot->stock_ticker : '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="balance">Starting Balance
                                        <a href="#" class="popover-link" data-toggle="popover" data-trigger="hover" data-content="Balance to start bot off for trading">
                                            <i class="far fa-question-circle"></i>
                                        </a>
                                    </label>
                                    <input type="number" name="balance" class="form-control" value="{{ isset($bot) ? $bot->initial_balance : 10 }}" {{ isset($bot) ? 'readonly' : '' }} required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="strategy-type">Strategy Type</label>

                                    @if(isset($bot))
                                        <input type="hidden" name="strategy-type" value="{{ $bot->strategyType->id }}">
                                    @endif
                                    <select name="strategy-type" class="form-control" {{ isset($bot) ? 'disabled' : '' }} onchange="updateStrategyPanels()">
                                        @foreach(\App\Models\StrategyType::all() as $strategy_type)
                                            <option value="{{ $strategy_type->id }}" {{ isset($bot) && $bot->strategyType->id === $strategy_type->id ? 'selected' : '' }}>
                                                {{ $strategy_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="interval">Interval
                                        <a href="#" class="popover-link" data-toggle="popover" data-trigger="hover" data-content="How often to run bot through strategy">
                                            <i class="far fa-question-circle"></i>
                                        </a>
                                    </label>
                                    <select name="interval" class="form-control" onchange="updateNextRun()">
                                        @foreach(\App\Models\Interval::all() as $interval)
                                            <option value="{{ $interval->id }}" {{ (isset($bot) && $bot->interval->id === $interval->id) ? 'selected' : '' }} data-seconds="{{ $interval->value }}">
                                                {{ $interval->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Next Run</label>
                                    <p class="next-run"><i></i></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="buy-order-length">Buy Order Length
                                        <a href="#" class="popover-link" data-toggle="popover" data-trigger="hover" data-content="How long to keep buy order open">
                                            <i class="far fa-question-circle"></i>
                                        </a>
                                    </label>
                                    <select name="buy-order-length" class="form-control">
                                        @foreach(\App\Models\OrderLength::all() as $order_length)
                                            <option value="{{ $order_length->id }}" {{ (isset($bot) && $bot->buyLength->id === $order_length->id) ? 'selected' : '' }}>
                                                {{ $order_length->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="buy-order-type">Buy Ordering Type</label>
                                    <select name="buy-order-type" class="form-control">
                                        <option value="1" {{ (isset($bot) && $bot->buyType->id === 1) ? 'selected' : '' }}>Market</option>
                                        <option value="2" {{ (isset($bot) && $bot->buyType->id === 2) ? 'selected' : '' }}>Limit</option>
                                        <option value="3" {{ (isset($bot) && $bot->buyType->id === 3) ? 'selected' : '' }}>Fractional by Price</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sell-order-length">Sell Order Length
                                        <a href="#" class="popover-link" data-toggle="popover" data-trigger="hover" data-content="How long to keep sell order open">
                                            <i class="far fa-question-circle"></i>
                                        </a>
                                    </label>
                                    <select name="sell-order-length" class="form-control">
                                        @foreach(\App\Models\OrderLength::all() as $order_length)
                                            <option value="{{ $order_length->id }}" {{ (isset($bot) && $bot->sellLength->id === $order_length->id) ? 'selected' : '' }}>
                                                {{ $order_length->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sell-order-type">Sell Ordering Type</label>
                                    <select name="sell-order-type" class="form-control">
                                        <option value="1" {{ (isset($bot) && $bot->sellType->id === 1) ? 'selected' : '' }}>Market</option>
                                        <option value="2" {{ (isset($bot) && $bot->sellType->id === 2) ? 'selected' : '' }}>Limit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('bots.view') }}" type="button" class="btn btn-cancel">Cancel</a>

                            <button type="submit" class="btn">{{ isset($bot) ? 'Update' : 'Create' }}</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel" id="panel-strategy-indicators">
                        <div class="panel-header">
                            <i class="fas fa-stream color-accent header-icon"></i> Indicators
                        </div>

                        <trade-bot-indicators
                            trade_bot_data="{{ isset($bot) ? json_encode($bot) : null }}"
                            indicators_data="{{ json_encode($indicators) }}">
                        </trade-bot-indicators>
                    </div>

                    <div class="panel" id="panel-strategy-code">
                        <div class="panel-header">
                            <i class="fas fa-code color-accent header-icon"></i> Custom Code
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="strategy">Strategy</label>

                                    @if(isset($bot))
                                        <input type="hidden" name="strategy" value="{{ $bot->strategy_id }}">
                                    @endif
                                    <select name="strategy" class="form-control" {{ isset($bot) ? 'readonly' : '' }}>
                                        @forelse($strategies as $strategy)
                                            <option value="{{ $strategy->id }}" {{ (isset($bot) && $bot->strategy_id === $strategy->id) ? 'selected' : '' }}>
                                                {{ $strategy->class_name }}
                                            </option>
                                        @empty
                                            <option value="">No strategies found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript">
        updateStrategyPanels();
        updateNextRun();

        /**
         * Hide/show panel depending on strategy type
         */
        function updateStrategyPanels()
        {
            let strategyType = $('select[name=strategy-type] option:selected').val();

            if (parseInt(strategyType) === 1) {
                $(`#panel-strategy-code`).hide();
                $(`#panel-strategy-indicators`).show();

            } else {
                $(`#panel-strategy-indicators`).hide();
                $(`#panel-strategy-code`).show();
            }
        }

        /**
         * Update text for next time trading bot will run
         */
        function updateNextRun()
        {
            let seconds = $('select[name=interval] option:selected').data('seconds');

            $('.next-run > i').text(moment().add(seconds, 'seconds').format("MMMM Do, h:mm a"));
        }
    </script>
@endpush
