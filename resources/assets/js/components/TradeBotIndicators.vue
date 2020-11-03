<template>
    <div class="indicators">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#buy-indicators">Buy Indicators</a>
            </li>
            <li>
                <a data-toggle="tab" href="#sell-indicators">Sell Indicators</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Buy indicators -->
            <div id="buy-indicators" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="add-buy-indicator">Add Buy Indicator</label>
                            <select id="add-buy-indicator" class="form-control" v-model="buy_selected" @change="addIndicator('buy')">
                                <option value=""></option>
                                <option v-for="(indicator, _) in indicators" v-bind:value="indicator" v-if="['both', 'buy'].includes(indicator.trade_side)">
                                    {{ indicator.key }} - {{ indicator.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <tr>
                        <th>Indicator</th>
                        <th>Input Data</th>
                        <th></th>
                    </tr>
                    <tr v-for="buy_indicator in buy_indicators">
                        <td>{{ buy_indicator.key }}</td>

                        <td v-if="buy_indicator.placeholder !== 'none_needed'">
                            <input type="text" :name="'buy-indicators[' + buy_indicator.key + ']'" class="form-control" :placeholder="buy_indicator.placeholder" :value="buy_indicator.value">
                        </td>
                        <td v-if="buy_indicator.placeholder === 'none_needed'">
                            <input type="hidden" :name="'buy-indicators[' + buy_indicator.key + ']'" class="form-control">
                            No input data needed
                        </td>

                        <td class="td-actions">
                            <a @click="deleteRow(buy_indicator.id, 'buy')" class="btn-fa pull-right">
                                <i class="fas fa-trash-alt color-red"></i>
                            </a>
                        </td>
                    </tr>

                    <tr v-if="buy_indicators.length === 0">
                        <td colspan="100" class="text-center"><i>No buy indicators</i></td>
                    </tr>
                </table>
            </div>

            <!-- Sell indicators -->
            <div id="sell-indicators" class="tab-pane fade in">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="add-sell-indicator">Add Sell Indicator</label>
                            <select id="add-sell-indicator" class="form-control" v-model="sell_selected" @change="addIndicator('sell')">
                                <option value=""></option>
                                <option v-for="(indicator, _) in indicators" v-bind:value="indicator" v-if="['both', 'sell'].includes(indicator.trade_side)">
                                    {{ indicator.key }} - {{ indicator.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <tr>
                        <th>Indicator</th>
                        <th>Input Data</th>
                        <th></th>
                    </tr>
                    <tr v-for="sell_indicator in sell_indicators">
                        <td>{{ sell_indicator.key }}</td>

                        <td v-if="sell_indicator.placeholder !== 'none_needed'">
                            <input type="text" :name="'sell-indicators[' + sell_indicator.key + ']'" class="form-control" :placeholder="sell_indicator.placeholder" :value="sell_indicator.value">
                        </td>
                        <td v-if="sell_indicator.placeholder === 'none_needed'">
                            <input type="hidden" :name="'sell-indicators[' + sell_indicator.key + ']'" class="form-control">
                            No input data needed
                        </td>

                        <td class="td-actions">
                            <a @click="deleteRow(sell_indicator.id, 'sell')" class="btn-fa pull-right">
                                <i class="fas fa-trash-alt color-red"></i>
                            </a>
                        </td>
                    </tr>

                    <tr v-if="sell_indicators.length === 0">
                        <td colspan="100" class="text-center"><i>No sell indicators</i></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'trade_bot_data',
            'indicators_data'
        ],
        data() {
            return {
                bot: this.trade_bot_data ? JSON.parse(this.trade_bot_data) : null,
                indicators: JSON.parse(this.indicators_data),
                buy_selected: {},
                sell_selected: {},
                buy_indicators: [],
                sell_indicators: [],
                id_counter: 0
            };
        },
        created: function () {
            let self = this;

            if (self.bot) {
                self.bot.indicators.map(function (indicator) {
                    if (indicator.signal_type === 'buy') {
                        self.buy_indicators.push({
                            id: indicator.indicator.id,
                            key: indicator.indicator.key,
                            placeholder: indicator.indicator.placeholder,
                            value: indicator.data
                        });
                    }
                });
                self.bot.indicators.map(function (indicator) {
                    if (indicator.signal_type === 'sell') {
                        self.sell_indicators.push({
                            id: indicator.indicator.id,
                            key: indicator.indicator.key,
                            placeholder: indicator.indicator.placeholder,
                            value: indicator.data
                        });
                    }
                });
            }
        },
        methods: {
            /**
             * Add a new indicator for a signal
             */
            addIndicator(signal_type)
            {
                this[`${signal_type}_indicators`].push({
                    id: this.id_counter,
                    key: this[`${signal_type}_selected`].key,
                    placeholder: this[`${signal_type}_selected`].placeholder,
                    value: null
                });

                this[`${signal_type}_selected`] = {};
                this.id_counter++;
            },
            /**
             * Remove a row from added indicators
             */
            deleteRow(id, signal_type)
            {
                this[`${signal_type}_indicators`].splice(_.findIndex(this[`${signal_type}_indicators`], function (indicator) {
                    return indicator.id === id;
                }), 1)
            }
        }
    }
</script>