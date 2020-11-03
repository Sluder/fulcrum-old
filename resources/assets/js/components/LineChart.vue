<script>
    import {generateChart, mixins} from 'vue-chartjs'

    // Extension to draw a vertical line on a point hover
    Chart.defaults.LineWithLine = Chart.defaults.line;
    Chart.controllers.LineWithLine = Chart.controllers.line.extend({
        draw: function (ease) {
            Chart.controllers.line.prototype.draw.call(this, ease);

            if (this.chart.tooltip._active && this.chart.tooltip._active.length) {
                let activePoint = this.chart.tooltip._active[0],
                    ctx = this.chart.ctx,
                    x = activePoint.tooltipPosition().x,
                    topY = this.chart.scales['y-axis-0'].top,
                    bottomY = this.chart.scales['y-axis-0'].bottom;

                // Draw line on hover
                ctx.save();
                ctx.beginPath();
                ctx.moveTo(x, topY);
                ctx.lineTo(x, bottomY);

                ctx.lineWidth = 1;
                ctx.strokeStyle = '#616161';
                ctx.stroke();

                ctx.restore();
            }
        }
    });

    const CustomLine = generateChart('custom-line', 'LineWithLine');

    export default {
        extends: CustomLine,
        mixins: [
            mixins.reactiveProp
        ],
        props: [
            'chartData',
            'options'
        ],
        mounted() {
            this.renderChart(this.chartData, this.options)
        },
        watch: {
            'chartData': {
                handler: function (data) {
                    this.$data._chart.destroy();
                    this.renderChart(data, this.options)
                }
            }
        }
    }
</script>