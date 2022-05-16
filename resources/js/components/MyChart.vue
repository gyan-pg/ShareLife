<template>
  <div class="p-container--chart" v-show="isShow">
    <h2 class="c-title c-title--pending u-mb-m">集計グラフ</h2>
    <p class="c-text--chart">総支出：{{ total }}円</p>
    <canvas id="myChart"></canvas>
  </div>
</template>

<script>
import Chart from "chart.js/dist/chart"
export default {
  props: {
    report: {
      type: Array
    }
  },
  data () {
    return {
      labels: [],
      data: [],
      chart: null,
      isShow: false
    }
  },
  computed: {
    total () {
      return this.$store.getters['payments/totalPayments']
    }
  },
  methods: {
    render () {
      let ctx = document.getElementById('myChart').getContext('2d')
      this.chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: this.labels,
        datasets: [{
            data: this.data,
            backgroundColor: ['#5BFF7F','#64F9C1','#D6FF58','#60EEFF','#5D99FF','#A16EFF','#FF69A3','#FF773E','#CBFFD3']// 色の数はラベルの個数よりは多く必要。
          }]
        },
        options: {
          responsive: true,
          tooltips: {
            callbacks: {
              label: (tooltipItem, data) => {
                return data.labels[tooltipItem.index]
                + ": "
                + data.datasets[0].data[tooltipItem.index]
                + " 円"
              }
            }
          },
          cutoutPercentage: 50
        }
      })
      this.isShow = true
      // データがないときはグラフを破棄
      if (this.data.length === 0) {
        this.chart.destroy()
        this.isShow = false
      }
    }
  },
  mounted () {
    this.labels = this.report[0]
    this.data = this.report[1]
    this.isShow = true
    this.render()
  },
  watch: {
    report (newValue) {
      this.labels = newValue[0]
      this.data = newValue[1]
      this.chart.destroy()
      this.render()
    }
  }
}
</script>