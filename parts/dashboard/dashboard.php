<div id="dashboard-wrapper">
  <div class="container pt-4">
    <div class="row">
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex mb-4 justify-content-between align-items-center">
              <div>
                <h5 class="text-light mb-2">Profit</h5>
                <h2 class="text-light">$23,523</h2>
              </div>
              <div class="card-icon" style="background-color: #446e7d">
                <span class="fa fa-usd text-light"></span>
              </div>
            </div>
            <div class="m-t-40">
              <span class="badge badge-pill badge-red">
                <i class="fas fa-arrow-down"></i> <span class="font-weight-semibold ml-1">6.71%</span>
              </span>
              <span class="text-light">Since last week</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex mb-4 justify-content-between align-items-center">
              <div>
                <h5 class="text-light mb-2">Trades</h5>
                <h2 class="text-light">15</h2>
              </div>
              <div class="card-icon" style="background-color: #429991">
                <span class="fa fa-globe text-light"></span>
              </div>
            </div>
            <div class="m-t-40">
              <span class="badge badge-pill badge-cyan">
                <i class="fas fa-arrow-up"></i> <span class="font-weight-semibold ml-1">11.21%</span>
              </span>
              <span class="text-light">Since last week</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex mb-4 justify-content-between align-items-center">
              <div>
                <h5 class="text-light mb-2">Portfolio</h5>
                <h2 class="text-light">34</h2>
              </div>
              <div class="card-icon" style="background-color: #7a30b3">
                <span class="fa fa-list-alt text-light"></span>
              </div>
            </div>
            <div class="m-t-40">
              <span class="badge badge-pill badge-red">
                <i class="fas fa-arrow-down"></i> <span class="font-weight-semibold ml-1">4.83%</span>
              </span>
              <span class="text-light">Since last week</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex mb-4 justify-content-between align-items-center">
              <div>
                <h5 class="text-light mb-2">Users</h5>
                <h2 class="text-light">1739</h2>
              </div>
              <div class="card-icon" style="background-color: #9fa448">
                <span class="fa fa-users text-light"></span>
              </div>
            </div>
            <div class="m-t-40">
              <span class="badge badge-pill badge-cyan">
                <i class="fas fa-arrow-up"></i> <span class="font-weight-semibold ml-1">1.29%</span>
              </span>
              <span class="text-light">Since last week</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container pt-2">
    <div class="row justify-content-between">
      <div class="col-md-8 dashboard-column">
        <div class="dashboard-content">
          <h4 class="text-light">Overview</h4>
          <div id="dashboard-profit-line-chart-wrapper">
            <canvas id="dashboard-profit-line-chart" height="120%"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4 dashboard-column">
        <div class="dashboard-content">
          <h4 class="text-light mb-5">Profit / Loss</h4>
          <canvas id="dashboard-profit-doughnut-chart"></canvas>
          <h2 class="text-light text-center mt-lg-5">21</h2>
          <p class="text-light text-center">Trades out of a total of 38 are profitable</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    drawLineChart("dashboard-profit-line-chart", "Prices", [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    drawDoughnutChart("dashboard-profit-doughnut-chart", "Profit / Loss", ["Profit", "Loss", "Neutral"], [80, 18, 2], ["#aaff2a", "#ff5959", "#bcbcbc"]);
    drawDoughnutChart("dashboard-portfolio-doughnut-chart", "Profit / Loss", ["Profit", "Loss", "Neutral"], [80, 18, 2], ["#aaff2a", "#ff5959", "#bcbcbc"]);
  });

  function drawLineChart(chartElement, title, xValues, yValues) {
    const chart = new Chart(chartElement, {
      type: "line",
      data: {
        labels: xValues,
        datasets: [{
          label: title,
          data: yValues,
          borderColor: "#3a8499",
          borderWidth: 1.5,
          fill: false
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
              zeroLineColor: "rgba(0, 0, 0, 0)"
            },
            ticks: { fontColor: "#ffffff"}
          }],
          yAxes: [{
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
              zeroLineColor: "rgba(0, 0, 0, 0)"
            },
            ticks: {fontColor: "#ffffff"}
          }]
        },
        elements: {
          point:{
            radius: 0
          }
        }
      }
    });
    return chart;
  }

  function drawDoughnutChart(chartElement, title, labels, data, colors) {
    const chart = new Chart(chartElement, {
      type: "doughnut",
      data: {
        labels: labels,
        datasets: [{
          label: title,
          data: data,
          borderWidth: 0,
          backgroundColor: colors
        }]
      },
      options: {
        aspectRatio: 1.5,
        legend: {
          display: false
        },
        cutoutPercentage: 90
      }
    });
    appendDoughnutCenterText(chart, data[0] + "%", "Profit");
    return chart;
  }

  function appendDoughnutCenterText(chart, firstLine, secondLine) {
    Chart.pluginService.register({
      beforeDraw: function(event) {
        if (event.chart !== chart) {
          return;
        }
        var width = chart.width,
          height = chart.height,
          ctx = chart.ctx;
        ctx.restore();
        ctx.font = "bold " + (height / 150).toFixed(2) + "em sans-serif";
        ctx.textBaseline = "middle";
        ctx.fillStyle = "#ededed";
        var firstLineX = Math.round((width - ctx.measureText(firstLine).width) / 2);
        var firstLineY = Math.round((height / 2) - (ctx.measureText('M').width / 1.5));
        ctx.fillText(firstLine, firstLineX, firstLineY);
        ctx.font = "bold " + (height / 200).toFixed(2) + "em sans-serif";
        ctx.fillStyle = "#b2b2b2";
        var secondLineX = Math.round((width - ctx.measureText(secondLine).width) / 2);
        var secondLineY = Math.round((height / 2) + (ctx.measureText('M').width / 1.5));
        ctx.fillText(secondLine, secondLineX, secondLineY);
        ctx.save();
      }
    });
  }
</script>