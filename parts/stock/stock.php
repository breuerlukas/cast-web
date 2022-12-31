<div id="stock-wrapper">
  <div id="stock-search-box">
    <input id="stock-search-input" placeholder="Search for stock..." type="text" class="form-control" value="" autocomplete="off">
    <button id="stock-search-button" type="button">
      <i class="fa fa-search"></i>
    </button>
    </div>
  <canvas id="stock-chart"></canvas>
</div>

<script>
  $(document).ready(function() {
    drawGraph([], []);
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
  var xValues = [];
  for (var i = 0; i < reviewPeriod; i++) {
    xValues[i] = i;
  }
  let request = new HttpRequest("http://lukasbreuer.de:8080/stock/prices", "POST", [{key: "Content-Type", value: "application/json"},
    {key: "API-KEY", value: Cookie.find("token")}], {stock: stock, reviewPeriod: reviewPeriod + ""});
  request.send(function (status, responseText) {
    var prices = JSON.parse(responseText).prices;
    drawGraph(xValues, prices);
  });
}

function drawGraph(xValues, yValues) {
  new Chart("stock-chart", {
    type: "line",
    data: {
      labels: xValues,
      datasets: [{
        label: "Prices",
        data: yValues,
        borderColor: "#3a8499",
        fill: false
      }]
    },
    options: {
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
</script>