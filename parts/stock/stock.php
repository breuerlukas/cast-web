<div id="stock-wrapper">
  <div id="stock-search-box">
    <input id="stock-search-input" placeholder="Search for stock..." type="text" class="form-control" value="" autocomplete="off">
    <button id="stock-search-button" type="button">
      <i class="fa fa-search"></i>
    </button>
  </div>
  <div id="stock-chart-wrapper">
    <canvas id="stock-chart"></canvas>
  </div>
  <div id="stock-prediction-chart-wrapper">
  </div>
</div>

<script>
$(document).ready(function() {
  if (window.sessionStorage.getItem("stock") != null) {
    displayStockChart(window.sessionStorage.getItem("stock"), 365);
    window.sessionStorage.removeItem("stock");
  }
});

document.getElementById("stock-search-input").addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("stock-search-button").click();
  }
});

document.getElementById("stock-search-button").onclick = function() {
  displayStockChart(document.getElementById("stock-search-input").value, 365);
}

function displayStockChart(stock, reviewPeriod) {
  var stockCanvas = document.getElementById("stock-chart");
  stockCanvas.getContext("2d").clearRect(0, 0, stockCanvas.width, stockCanvas.height);
  document.getElementById("stock-prediction-chart-wrapper").innerHTML = "";
  var loader = document.createElement("div");
  loader.id = "stock-chart-loader";
  loader.classList.add("loader");
  document.getElementById("stock-wrapper").appendChild(loader);
  var xValues = [];
  for (var i = 0; i < reviewPeriod; i++) {
    xValues[i] = i;
  }
  let request = new HttpRequest("http://lukasbreuer.de:8080/stock/prices", "POST", [{key: "Content-Type", value: "application/json"},
    {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, reviewPeriod: reviewPeriod + ""});
  request.send(function (status, responseText) {
    var prices = JSON.parse(responseText).prices;
    let request = new HttpRequest("http://lukasbreuer.de:8080/stock/predictions", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, tradeType: "BUY", reviewPeriod: reviewPeriod + ""});
    request.send(function (status, responseText) {
        document.getElementById("stock-wrapper").removeChild(loader);
      drawGraph("stock-chart", "Prices", xValues, prices);
      if (status != 404) {
        var predictions = JSON.parse(responseText).predictions;
        var predictionCanvas = document.createElement('canvas');
        predictionCanvas.id = "stock-prediction-chart";
        document.getElementById("stock-prediction-chart-wrapper").appendChild(predictionCanvas);
        drawGraph("stock-prediction-chart", "Predictions", xValues, predictions);
      }
    });
  });
}

function drawGraph(chart, title, xValues, yValues) {
  new Chart(chart, {
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
      elements: {
        point:{
          radius: 0
        }
      }
    }
  });
}
</script>""