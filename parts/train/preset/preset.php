<div id="train-preset-wrapper">
  <div id="train-preset-search-box">
    <input id="train-preset-search-input" placeholder="Search for stock..." type="text" class="form-control" value="" autocomplete="off">
    <button id="train-preset-search-button" type="button">
      <i class="fa fa-search"></i>
    </button>
  </div>
  <div id="train-preset-headline-wrapper">
    <h2 id="train-preset-chart-headline"></h2>
  </div>
  <div id="train-preset-chart-wrapper">
  </div>
</div>

<script>
  const EVALUATION_REVIEW_PERIOD = 365;

  document.getElementById("train-preset-search-input").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
      document.getElementById("train-preset-search-button").click();
    }
  });

  document.getElementById("train-preset-search-button").onclick = function() {
    var searchValue = document.getElementById("train-preset-search-input").value;
    display(searchValue, REVIEW_PERIOD);
  }

  function display(stock, reviewPeriod) {
    resetContent();
    displayLoader();
    var xValues = [];
    for (var i = 0; i < reviewPeriod; i++) {
      xValues[i] = i;
    }
    let request = new HttpRequest("https://lukasbreuer.de:8080/stock/prices", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, reviewPeriod: reviewPeriod + ""});
    request.send(function (priceStatus, priceResponse) {
      var prices = JSON.parse(priceResponse).prices;
      displayStockCanvas(xValues, prices);
      document.getElementById("train-preset-wrapper").removeChild(document.getElementById("train-preset-chart-loader"));
      document.getElementById("train-preset-chart-headline").innerHTML = "Prices";
    });
  }

  function resetContent() {
    document.getElementById("train-preset-chart-wrapper").innerHTML = "";
    document.getElementById("train-preset-chart-headline").innerHTML = "";
  }

  function displayLoader() {
    var loader = document.createElement("div");
    loader.id = "train-preset-chart-loader";
    loader.classList.add("loader");
    document.getElementById("train-preset-wrapper").appendChild(loader);
  }

  function displayStockCanvas(xValues, prices) {
    var stockCanvas = document.createElement('canvas');
    stockCanvas.id = "train-preset-chart";
    document.getElementById("train-preset-chart-wrapper").appendChild(stockCanvas);
    return drawGraph("train-preset-chart", "Prices", xValues, prices);
  }

  var firstX = -1;

  function drawGraph(chartElement, title, xValues, yValues) {
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
        maintainAspectRatio: false,
        scales: {
          xAxes: [{gridLines: { color: "#131c2b" }}],
          yAxes: [{gridLines: { color: "#131c2b" }}]
        },
        onClick: (e) => {
          const canvasPosition = Chart.helpers.getRelativePosition(e, chart);
          var xAxis = chart.scales['x-axis-0'];
          var yAxis = chart.scales['y-axis-0'];

          // Substitute the appropriate scale IDs
          const dataX = xAxis.getValueForPixel(canvasPosition.x);
          console.log(dataX);
          if (firstX === -1) {
            firstX = dataX;
            return;
          }
          var context = chart.ctx;
          context.fillStyle = "rgba(0, 0, 0, 0.4)";
          context.fillRect(xAxis.getPixelForValue(firstX), yAxis.getPixelForValue(yAxis.max),
            xAxis.getPixelForValue(dataX - firstX), yAxis.getPixelForValue(0));
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
</script>