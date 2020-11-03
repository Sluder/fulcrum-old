/**
 * Imports
 */
require('./bootstrap');
window.Vue = require('vue');

/**
 * Vue configurations
 */
import moment from 'moment'

Vue.component('trade-bots-dashboard', require('./components/TradeBotsDashboard.vue'));
Vue.component('trade-bot-indicators', require('./components/TradeBotindicators.vue'));
Vue.component('line-chart', require('./components/LineChart.vue'));

const app = new Vue({
    el: '#app'
});

Vue.config.devtools = false;
Vue.prototype.moment = moment;