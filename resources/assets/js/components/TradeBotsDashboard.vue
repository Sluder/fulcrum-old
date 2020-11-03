<template>
    <div class="row">
        <!-- Trading bots table -->
        <div class="col-md-7">
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-robot color-accent header-icon"></i> Trading Bots

                    <a class="btn-fa pull-right" href="/bots/form" title="Add new trading bot">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>

                <table class="table table-bots">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Ticker</th>
                            <th>Strategy</th>
                            <th>Start $</th>
                            <th>Current $</th>
                            <th>Profit</th>
                            <th>Change</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="bot in bots" class="hover" v-bind:class="{selected: selected_bot_id === bot.id}" v-on:click="selectBot(bot)">
                            <td>{{ ('0000' + bot.id).substr(-4, 4) }}</td>
                            <td>
                                <code class="buy" v-if="[1, 2].includes(parseInt(bot.state.id))">{{ bot.state.name }}</code>
                                <code class="sell" v-if="[3, 4].includes(parseInt(bot.state.id))">{{ bot.state.name }}</code>
                            </td>
                            <td>{{ bot.stock_ticker }}</td>
                            <td>{{ bot.strategy_type.name }}</td>
                            <td>${{ parseFloat(bot.trade_start_balance).toFixed(2) }}</td>
                            <td>${{ parseFloat(bot.trade_current_balance).toFixed(2) }}</td>

                            <td :class="bot.dollar_change >= 0 ? 'color-green' : 'color-red'">
                                <i class="fas fa-chevron-up" v-if="bot.dollar_change > 0"></i>
                                <i class="fas fa-chevron-down" v-else-if="bot.dollar_change < 0"></i>
                                <i class="fas fa-minus" v-else></i>
                                ${{ parseFloat(Math.abs(bot.dollar_change)).toFixed(2) }}
                            </td>

                            <td :class="bot.percent_change >= 0 ? 'color-green' : 'color-red'">
                                <i class="fas fa-chevron-up" v-if="bot.percent_change > 0"></i>
                                <i class="fas fa-chevron-down" v-else-if="bot.percent_change < 0"></i>
                                <i class="fas fa-minus" v-else></i>
                                {{ parseFloat(Math.abs(bot.percent_change)).toFixed(2) }}%
                            </td>

                            <td class="td-actions">
                                <a :href="'/bots/' + bot.id + '/form'" class="btn-fa pull-right" v-if="parseInt(bot.should_delete) === 0" title="Update bot configurations">
                                    <i class="fas fa-cog"></i>
                                </a>
                                <a href="#" class="btn-fa pull-right color-red" v-if="parseInt(bot.should_delete) === 1" title="Bot is scheduled for deletion">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </a>
                            </td>
                        </tr>

                        <tr v-if="!bots.length">
                            <td colspan="100" class="text-center"><i>No trading bots found</i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-5">
            <!-- Profit ticks for selected trading bot -->
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-chart-area color-accent header-icon"></i> Balance History

                    <select class="form-control profit-chart-select" v-model="selected_interval" v-on:change="populateBalanceTicks()">
                        <option value="D" selected>Day</option>
                        <option value="M">Month</option>
                    </select>
                </div>

                <line-chart
                        :chartData="{labels: profit_chart_labels, datasets: [{fill: false, borderColor: '#27d8ac', borderWidth: 2, pointBorderColor: '#27d8ac', pointBackgroundColor: '#27d8ac', pointRadius: 1, data: profit_chart_data}]}"
                        :options="profit_chart_options"
                        :height="150">
                </line-chart>
            </div>

            <!-- Trades for selected trading bot -->
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-hands-helping color-accent header-icon"></i> Completed Trades
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th width="35%">Closed</th>
                            <th width="15%">Bought At</th>
                            <th width="15%">Sold At</th>
                            <th width="15%">Profit</th>
                            <th width="20%">Percent Profit</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="trade in selected_bot_trades">
                            <td>{{ moment(trade.sell_order.closed).format('MMMM Do, h:mm a') }}</td>
                            <td>${{ parseFloat(trade.buy_order.rate).toFixed(2) }}</td>
                            <td>${{ parseFloat(trade.sell_order.rate).toFixed(2) }}</td>

                            <td :class="trade.dollar_change >= 0 ? 'color-green' : 'color-red'">
                                <i class="fas fa-chevron-up" v-if="trade.dollar_change > 0"></i>
                                <i class="fas fa-chevron-down" v-else-if="trade.dollar_change < 0"></i>
                                <i class="fas fa-minus" v-else></i>
                                ${{ parseFloat(Math.abs(trade.dollar_change)).toFixed(2) }}
                            </td>

                            <td :class="trade.percent_change >= 0 ? 'color-green' : 'color-red'">
                                <i class="fas fa-chevron-up" v-if="trade.percent_change > 0"></i>
                                <i class="fas fa-chevron-down" v-else-if="trade.percent_change < 0"></i>
                                <i class="fas fa-minus" v-else></i>
                                {{ parseFloat(Math.abs(trade.percent_change)).toFixed(2) }}%
                            </td>
                        </tr>

                        <tr v-if="!selected_bot_id">
                            <td colspan="100" class="text-center"><i>Select trading bot to view trades</i></td>
                        </tr>

                        <tr v-if="selected_bot_id && Object.keys(selected_bot_trades).length === 0">
                            <td colspan="100" class="text-center"><i>No trades found for trading bot {{ ('0000' + selected_bot_id).substr(-4, 4) }}</i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'trade_bots'
        ],
        data() {
            return {
                bots: JSON.parse(this.trade_bots),
                selected_bot_id: null,
                selected_bot_trades: [],

                selected_interval: 'D',

                profit_chart_options: {},
                profit_chart_labels: [],
                profit_chart_data: []
            };
        },
        mounted: function () {
            window.addEventListener('load', () => {
                $('.profit-chart-select').prop('disabled', true);
            })
        },
        created: function () {
            let self = this;

            self.profit_chart_options = {
                legend: {
                    display: false
                },
                elements: {
                    line: {
                        tension: 0
                    },
                    point: {
                        radius: 0
                    }
                },
                tooltips: {
                    mode: 'x-axis',
                    backgroundColor: '#3c3c3c',
                    displayColors: false,
                    callbacks: {
                        title: function() {},
                        label: function(tooltip) {
                            return '$' + parseFloat(tooltip.yLabel).toFixed(2) + ' at ' + tooltip.xLabel;
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            color: "#fff",
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 15
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 50,
                            callback: function (value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }]
                },
                pan: {
                    enabled: true,
                    mode: 'xy'
                },
                zoom: {
                    enabled: false
                }
            };
        },
        methods: {
            /**
             * Sets the bot that is used for profit chart and recent trades
             */
            selectBot(bot) {
                let self = this;

                $('.profit-chart-select').prop('disabled', false);

                this.selected_bot_id = bot.id;
                this.selected_bot_trades = bot.trades;

                $.each(this.selected_bot_trades, function (key, trade) {
                    trade.dollar_change = trade.sell_order.rate - trade.buy_order.rate;
                    trade.percent_change = self.percentChange(trade.buy_order.rate, trade.sell_order.rate);
                });

                self.populateBalanceTicks();
            },
            /**
             * Calculate percentage change in two numbers
             */
            percentChange(start_num, end_num)
            {
                start_num = parseFloat(start_num);
                end_num = parseFloat(end_num);

                if (start_num === parseFloat(0)) {
                    return 0;
                }

                return (((end_num - start_num) / start_num) * 100).toFixed(2);
            },
            /**
             * Populate profit ticks chart
             */
            populateBalanceTicks()
            {
                let self = this;

                self.profit_chart_labels = [];
                self.profit_chart_data = [];

                let balance_ticks = $.ajax({
                    type: 'GET',
                    async: false,
                    url: `/bots/${this.selected_bot_id}/balance-ticks`,
                    data: {
                        'interval': this.selected_interval
                    },
                    dataType: 'json'
                }).responseJSON;

                $.each(balance_ticks, function (key, tick) {
                    switch (self.selected_interval) {
                        case 'D':
                            self.profit_chart_labels.push(self.moment(tick.time).format('h:00 a'));
                            break;
                        case 'M':
                            self.profit_chart_labels.push(self.moment(tick.time).format('MMM Do'));
                            break;
                    }
                    self.profit_chart_data.push(tick.balance);
                });

            }
        }
    }
</script>