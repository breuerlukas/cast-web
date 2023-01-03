<div id="trade-wrapper">
  <div id="trade-header">
    <div id="trade-search-box">
      <input id="trade-search-input" placeholder="Search for stock..." type="text" class="form-control" value="" autocomplete="off">
      <button type="button">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>
  <ul id="trade-table-description">
    <li>
      <div>
        <span>Stock</span> <button id="trade-filter-stock" class="trade-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
    <li>
      <div>
        <span>Purchase Time</span> <button id="trade-filter-purchase-time" class="trade-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
    <li>
      <div>
        <span>Purchase Price</span> <button id="trade-filter-purchase-price" class="trade-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
    <li>
      <div>
        <span>Current Price</span> <button id="trade-filter-current-price" class="trade-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
    <li>
      <div>
        <span>Profit / Loss</span> <button id="trade-filter-profit-loss" class="trade-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
  </ul>
  <ul id="trade-list">
  </ul>
</div>

<script>
  $(document).ready(function() {
    fetchTradeData(function() {
      registerAllFilters();
      for (var i = 0; i < filters.length; i++) {
        let filter = filters[i];
        document.getElementById("trade-filter-" + filter.elementName).addEventListener("click", event => {
          changeFilter(filter);
        });
      }
      changeFilter(filters.filter(filter => filter.elementName === "stock")[0]);
    });
  });

  function fetchTradeData(callback) {
    let request = new HttpRequest("http://lukasbreuer.de:8080/trades/current", "GET", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}]);
    request.send(function (status, responseText) {
      trades = JSON.parse(responseText).trades;
      for (let i = 0; i < trades.length; i++) {
        let trade = trades[i];
        trade.tradeTime = Number(trade.tradeTime);
        trade.tradePrice = parseFloat(trade.tradePrice);
        trade.currentPrice = parseFloat(trade.currentPrice);
        trade.profitLoss = trade.currentPrice - trade.tradePrice;
      }
      callback();
    });
  }

  var trades;
  var filters = [];
  var currentFilter;
  var currentFilterDirection;
  var currentFilterIcon;
  var currentNameFilter = "";

  function displayTrades() {
    var sortedTrades = trades.sort(sort(currentFilter.variableName));
    if (currentFilterDirection) {
      sortedTrades.reverse();
    }
    document.getElementById("trade-list").innerHTML = "";
    for (var i = 0; i < sortedTrades.length; i++) {
      var entry = sortedTrades[i];
      if(!entry.stock.toLowerCase().startsWith(currentNameFilter.toLowerCase())) {
          continue;
      }
      displayTrade(entry.id, entry.stock, entry.tradeTime, entry.tradePrice, entry.currentPrice, entry.profitLoss);
    }
  }

  function sort(field) {
    return function(first, second) {
      return (first[field] > second[field]) - (first[field] < second[field]);
    };
  }

  function displayTrade(tradeId, stockName, purchaseTime, purchasePrice, currentPrice, profitLoss) {
    var entry = document.createElement('li');
    entry.addEventListener("click", event => {
      window.sessionStorage.setItem("stock", stockName);
      window.location.href = "/?page=stock/";
    });
    appendCell(entry, "stock", stockName);
    var date = new Date(purchaseTime);
    appendCell(entry, "purchase-time", date.getDate().pad() + "." + (date.getMonth() + 1).pad() + "." + date.getFullYear().pad());
    appendCell(entry, "purchase-price", purchasePrice.toFixed(3));
    appendCell(entry, "current-price", currentPrice.toFixed(3));
    var lossSpan = appendCell(entry, "profit-loss", profitLoss.toFixed(3));
    if (profitLoss > 0) {
      lossSpan.classList.add("text-success");
    } else {
      lossSpan.classList.add("text-danger");
    }
    document.getElementById("trade-list").appendChild(entry);
  }

  Number.prototype.pad = function() {
    return (this < 10 ? "0" : "") + this;
  }

  function appendCell(entry, identifier, information) {
    var div = document.createElement("div");
    var span = document.createElement("span");
    span.innerText = information;
    span.classList.add("trade-list-" + identifier);
    div.appendChild(span);
    entry.appendChild(div);
    return span;
  }

  function registerAllFilters() {
    registerFilter("stock", "stock");
    registerFilter("tradeTime", "purchase-time");
    registerFilter("tradePrice", "purchase-price");
    registerFilter("currentPrice", "current-price");
    registerFilter("profitLoss", "profit-loss");
  }

  function registerFilter(variableName, elementName) {
    filters.push({
      variableName: variableName,
      elementName: elementName
    });
  }

  function changeFilter(filter) {
    if (currentFilter === filter) {
      currentFilterDirection = !currentFilterDirection;
      if (currentFilterDirection) {
        currentFilterIcon.classList.add("fa-sort-desc");
        currentFilterIcon.classList.remove("fa-sort-asc");
      } else {
        currentFilterIcon.classList.remove("fa-sort-desc");
        currentFilterIcon.classList.add("fa-sort-asc");
      }
    } else {
      if (currentFilterIcon != null) {
        currentFilterIcon.classList.remove("fa-sort-asc");
        currentFilterIcon.classList.remove("fa-sort-desc");
        currentFilterIcon.classList.add("fa-sort");
      }
      currentFilter = filter;
      currentFilterDirection = filter.elementName === "stock" || filter.elementName === "purchase-time";
      currentFilterIcon = document.getElementById("trade-filter-" + filter.elementName).firstChild;
      changeFilter(filter);
      return;
    }
    displayTrades();
  }

  document.getElementById("trade-search-input").oninput = function(event) {
    currentNameFilter = event.target.value;
    displayTrades();
  };
</script>