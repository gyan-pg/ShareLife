<template>
<div>
  <h1>グラフのテスト</h1>
  <div style="position:relative;width:250px;height:250px">
    <canvas id="myChart"></canvas>
  </div>
</div>
</template>

<script>
import Chart from "chart.js/dist/chart"
export default {
  mounted () {
    let ctx = document.getElementById('myChart').getContext('2d')
    let myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['red', 'green', 'blue'],
      datasets: [{
          data: [10,20,30],
          backgroundColor: ['#f00', '#0f0', '#00f'],
        }]
      },
      options: {
        cutoutPercentage: 80
      }
    })
    console.log($('#myChart'))
  }
}
</script>