<div id="stock-wrapper">
  <div id="stock-search-box">
    <input id="stock-search-input" placeholder="Search for stock..." type="text" class="form-control" value="" autocomplete="off">
    <button id="stock-search-button" type="button">
      <i class="fa fa-search"></i>
    </button>
  </div>
  <div id="stock-headline-wrapper">
    <h2 id="stock-chart-headline"></h2>
    <button id="stock-model-settings"><i id="stock-model-settings-icon"></i></button>
  </div>
  <div id="stock-chart-wrapper">
  </div>
  <div id="stock-prediction-chart-wrapper">
  </div>
</div>

<script>
const REVIEW_PERIOD = 365;

$(document).ready(function() {
  var urlParameter = new URLSearchParams(window.location.search);
  if (urlParameter.has("name")) {
    display(urlParameter.get("name"), REVIEW_PERIOD);
  }
});

document.getElementById("stock-search-input").addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("stock-search-button").click();
  }
});

document.getElementById("stock-search-button").onclick = function() {
  var searchValue = document.getElementById("stock-search-input").value;
  window.location.href = "/stock?name=" + searchValue;
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
    let request = new HttpRequest("https://lukasbreuer.de:8080/stock/predictions", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, reviewPeriod: reviewPeriod + ""});
    request.send(function (predictionStatus, predictionResponse) {
      let request = new HttpRequest("https://lukasbreuer.de:8080/trades/latest", "POST", [{key: "Content-Type", value: "application/json"},
        {key: "API-KEY", value: Cookie.find("token")}], {stock: stock});
      request.send(function (latestTradesStatus, latestTradesResponse) {
        var prices = JSON.parse(priceResponse).prices;
        var latestTrades = JSON.parse(latestTradesResponse);
        displayStockCanvas(xValues, prices, calculateBoughtLine(latestTrades));
        document.getElementById("stock-wrapper").removeChild(document.getElementById("stock-chart-loader"));
        document.getElementById("stock-chart-headline").innerHTML = "Prices / Predictions";
        document.getElementById("stock-model-settings-icon").classList.add("fa", "fa-gear");
        if (predictionStatus != 404) {
          var predictions = JSON.parse(predictionResponse).predictions;
          displayPredictionCanvas(xValues, predictions);
        }
      });
    });
  });
}

function calculateBoughtLine(latestTrades) {
  if (!Object.hasOwn(latestTrades, "latestBuy")) {
    return [];
  }
  var latestBuy = latestTrades.latestBuy;
  if (Object.hasOwn(latestTrades, "latestSell")) {
    var latestSell = latestTrades.latestSell;
    if (latestSell.tradeTime > latestBuy.tradeTime) {
      return [];
    }
  }
  var difference = latestBuy.tradeTime - (new Date().getTime() - (REVIEW_PERIOD * 1000 * 60 * 60 * 24));
  var convertedTime = difference / (1000 * 60 * 60 * 24);
  var lines = [];
  if (difference > 0) {
    lines.push(parseInt(convertedTime));
  }
  return lines;
}

function resetContent() {
  document.getElementById("stock-chart-wrapper").innerHTML = "";
  var predictionChartWrapper = document.getElementById("stock-prediction-chart-wrapper");
  predictionChartWrapper.innerHTML = "";
  predictionChartWrapper.setAttribute("style", "height: 0");
  document.getElementById("stock-chart-headline").innerHTML = "";
  document.getElementById("stock-model-settings-icon").classList.remove("fa", "fa-gear");
}

function displayLoader() {
  var loader = document.createElement("div");
  loader.id = "stock-chart-loader";
  loader.classList.add("loader");
  document.getElementById("stock-wrapper").appendChild(loader);
}

function displayStockCanvas(xValues, prices, latestBuy) {
  var stockCanvas = document.createElement('canvas');
  stockCanvas.id = "stock-chart";
  document.getElementById("stock-chart-wrapper").appendChild(stockCanvas);
  return drawGraph("stock-chart", "Prices", xValues, prices, latestBuy);
}

function displayPredictionCanvas(xValues, predictions) {
  document.getElementById("stock-prediction-chart-wrapper").setAttribute("style", "");
  var predictionCanvas = document.createElement('canvas');
  predictionCanvas.id = "stock-prediction-chart";
  document.getElementById("stock-prediction-chart-wrapper").appendChild(predictionCanvas);
  return drawGraph("stock-prediction-chart", "Predictions", xValues, predictions, []);
}

var firstX = -1;

function drawGraph(chartElement, title, xValues, yValues, lines) {
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
    lineAtIndex: lines,
    options: {
      maintainAspectRatio: false,
      legend: {
        display: false
      },
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

document.getElementById("stock-model-settings").addEventListener("click", event => {
  var stock = window.sessionStorage.getItem("stock").toUpperCase();
  let request = new HttpRequest("https://lukasbreuer.de:8080/model/exists", "POST", [{key: "Content-Type", value: "application/json"},
    {key: "API-KEY", value: Cookie.find("token")}], {stock: stock});
  request.send(function (status, responseText) {
    var exists = JSON.parse(responseText).exists;
    if (exists) {
      let request = new HttpRequest("https://lukasbreuer.de:8080/model/find", "POST", [{key: "Content-Type", value: "application/json"},
        {key: "API-KEY", value: Cookie.find("token")}], {stock: stock});
      request.send(function (status, responseText) {
        var result = JSON.parse(responseText);
        openModelSettingsWindow(stock, result.buyModelPath, result.sellModelPath, result.buyReviewPeriod,
          result.sellReviewPeriod, result.buyTradePredictionMinimum, result.sellTradePredictionMinimum);
      });
      return;
    }
    openModelAddWindow(stock);
  });
});

function openModelAddWindow(stock) {
  openModelWindow(function () {
    let request = new HttpRequest("https://lukasbreuer.de:8080/model/add", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, buyModelPath: $("#model-buy-path").val(),
        sellModelPath: $("#model-sell-path").val(), buyReviewPeriod: $("#model-buy-review-period").val(),
        sellReviewPeriod: $("#model-sell-review-period").val(), buyTradePredictionMinimum: $("#model-buy-trade-prediction-minimum").val(),
        sellTradePredictionMinimum: $("#model-sell-trade-prediction-minimum").val()});
    request.send(function (status, responseText) {swal("Added!", "You have successfully added that model!", "success")});
  });
}

function openModelSettingsWindow(stock, buyModelPath, sellModelPath, buyReviewPeriod, sellReviewPeriod, buyTradePredictionMinimum, sellTradePredictionMinimum) {
  window.sessionStorage.setItem("buyModelPath", buyModelPath);
  window.sessionStorage.setItem("sellModelPath", sellModelPath);
  window.sessionStorage.setItem("buyReviewPeriod", buyReviewPeriod);
  window.sessionStorage.setItem("sellReviewPeriod", sellReviewPeriod);
  window.sessionStorage.setItem("buyTradePredictionMinimum", buyTradePredictionMinimum);
  window.sessionStorage.setItem("sellTradePredictionMinimum", sellTradePredictionMinimum);
  openModelWindow(function () {
    let request = new HttpRequest("https://lukasbreuer.de:8080/model/update", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, buyModelPath: $("#model-buy-path").val(),
        sellModelPath: $("#model-sell-path").val(), buyReviewPeriod: $("#model-buy-review-period").val(),
        sellReviewPeriod: $("#model-sell-review-period").val(), buyTradePredictionMinimum: $("#model-buy-trade-prediction-minimum").val(),
        sellTradePredictionMinimum: $("#model-sell-trade-prediction-minimum").val()});
    request.send(function (status, responseText) {swal("Adjusted!", "You have successfully adjusted that model!", "success")});
  });
}

function openModelWindow(callback) {
  const div = document.createElement("div");
  fetch('/parts/stock/model_add.html')
    .then(response => response.text())
    .then(text => $(div).html(text));
  swal({
    title: "Model",
    content: div,
    buttons: {
      cancel: "Cancel",
      confirm: "OK"
    },
  }).then(result => {
    if (result === true) {
      if (checkEmptyFields()) {
        swal({
          title: "Oops...",
          text: "Please fill out all fields before you create the entry!",
          icon: "error"
        });
        return;
      }
      callback();
    }
  });
}

function checkEmptyFields() {
  return $("#model-buy-path").val() == "" || $("#model-sell-path").val() == "" || $("#model-buy-review-period").val() == "" ||
    $("#model-sell-review-period").val() == "" || $("#model-buy-trade-prediction-minimum").val() == "" ||
    $("#model-sell-trade-prediction-minimum").val() == "";
}
</script>