<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';

export default {
  props: {
    className: {
      type: String,
      default: 'chart',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '300px',
    },
  },
  data() {
    return {
      chart: null,
    };
  },
  mounted() {
    this.initChart();
    this.__resizeHandler = debounce(() => {
      if (this.chart) {
        this.chart.resize();
      }
    }, 100);
    window.addEventListener('resize', this.__resizeHandler);
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    window.removeEventListener('resize', this.__resizeHandler);
    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');

      this.chart.setOption({
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)',
        },
        legend: {
          left: 'center',
          bottom: '10',
          data: ['Constituição Federal de 1988', 'Código de Processo Civil', 'Código Processo Penal', 'POLÍCIA MILITAR GOIÁS', 'Técnico Judiciário do TRF3'],
        },
        calculable: true,
        series: [
          {
            name: '10 Mais Acessadas',
            type: 'pie',
            roseType: 'radius',
            radius: [15, 95],
            center: ['50%', '38%'],
            data: [
              { value: 700, name: 'Constituição Federal de 1988' },
              { value: 500, name: 'Código de Processo Civil' },
              { value: 468, name: 'Código Processo Penal' },
              { value: 445, name: 'POLÍCIA MILITAR GOIÁS' },
              { value: 392, name: 'Técnico Judiciário do TRF3' },
            ],
            animationEasing: 'cubicInOut',
            animationDuration: 2600,
          },
        ],
      });
    },
  },
};
</script>
