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
$(document).ready(function() {
  if (window.sessionStorage.getItem("stock") != null) {
    display(window.sessionStorage.getItem("stock"), 365);
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
  window.sessionStorage.setItem("stock", searchValue);
  display(searchValue, 365);
}

function display(stock, reviewPeriod) {
  resetContent();
  displayLoader();
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
      document.getElementById("stock-wrapper").removeChild(document.getElementById("stock-chart-loader"));
      document.getElementById("stock-chart-headline").innerHTML = "Prices / Predictions";
      document.getElementById("stock-model-settings-icon").classList.add("fa", "fa-gear");
      displayStockCanvas(xValues, prices);
      if (status != 404) {
        var predictions = JSON.parse(responseText).predictions;
        displayPredictionCanvas(xValues, predictions);
      }
    });
  });
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

function displayStockCanvas(xValues, prices) {
  var stockCanvas = document.createElement('canvas');
  stockCanvas.id = "stock-chart";
  document.getElementById("stock-chart-wrapper").appendChild(stockCanvas);
  drawGraph("stock-chart", "Prices", xValues, prices);
}

function displayPredictionCanvas(xValues, predictions) {
  document.getElementById("stock-prediction-chart-wrapper").setAttribute("style", "");
  var predictionCanvas = document.createElement('canvas');
  predictionCanvas.id = "stock-prediction-chart";
  document.getElementById("stock-prediction-chart-wrapper").appendChild(predictionCanvas);
  drawGraph("stock-prediction-chart", "Predictions", xValues, predictions);
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

document.getElementById("stock-model-settings").addEventListener("click", event => {
  var stock = window.sessionStorage.getItem("stock").toUpperCase();
  let request = new HttpRequest("http://lukasbreuer.de:8080/model/exists", "POST", [{key: "Content-Type", value: "application/json"},
    {key: "API-KEY", value: Cookie.find("token")}], {stock: stock});
  request.send(function (status, responseText) {
    var exists = JSON.parse(responseText).exists;
    if (exists) {
      let request = new HttpRequest("http://lukasbreuer.de:8080/model/find", "POST", [{key: "Content-Type", value: "application/json"},
        {key: "API-KEY", value: Cookie.find("token")}], {stock: stock});
      request.send(function (status, responseText) {
        var result = JSON.parse(responseText);
        openModelSettingsWindow(stock, result.buyModelPath, result.sellModelPath, result.reviewPeriod,
          result.buyTradePredictionMinimum, result.sellTradePredictionMinimum);
      });
      return;
    }
    openModelAddWindow(stock);
  });
});

function openModelAddWindow(stock) {
  openModelWindow(function () {
    let request = new HttpRequest("http://lukasbreuer.de:8080/model/add", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, buyModelPath: $("#model-buy-path").val(),
        sellModelPath: $("#model-sell-path").val(), reviewPeriod: $("#model-review-period").val(),
        buyTradePredictionMinimum: $("#model-buy-trade-prediction-minimum").val(), sellTradePredictionMinimum: $("#model-sell-trade-prediction-minimum").val()});
    request.send(function (status, responseText) {swal("Added!", "You have successfully added that model!", "success")});
  });
}

function openModelSettingsWindow(stock, buyModelPath, sellModelPath, reviewPeriod, buyTradePredictionMinimum, sellTradePredictionMinimum) {
  window.sessionStorage.setItem("buyModelPath", buyModelPath);
  window.sessionStorage.setItem("sellModelPath", sellModelPath);
  window.sessionStorage.setItem("reviewPeriod", reviewPeriod);
  window.sessionStorage.setItem("buyTradePredictionMinimum", buyTradePredictionMinimum);
  window.sessionStorage.setItem("sellTradePredictionMinimum", sellTradePredictionMinimum);
  openModelWindow(function () {
    let request = new HttpRequest("http://lukasbreuer.de:8080/model/update", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, buyModelPath: $("#model-buy-path").val(),
        sellModelPath: $("#model-sell-path").val(), reviewPeriod: $("#model-review-period").val(),
        buyTradePredictionMinimum: $("#model-buy-trade-prediction-minimum").val(), sellTradePredictionMinimum: $("#model-sell-trade-prediction-minimum").val()});
    request.send(function (status, responseText) {swal("Adjusted!", "You have successfully adjusted that model!", "success")});
  });
}

function openModelWindow(callback) {
  const div = document.createElement("div");
  fetch('parts/stock/model_add.html')
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
  return $("#model-buy-path").val() == "" || $("#model-sell-path").val() == "" || $("#model-review-period").val() == "" ||
    $("#model-buy-trade-prediction-minimum").val() == "" || $("#model-sell-trade-prediction-minimum").val() == "";
}

window.onbeforeunload = function (event) {
  window.sessionStorage.removeItem("stock");
};
</script>