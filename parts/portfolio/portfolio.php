<div id="portfolio-wrapper">
  <div id="portfolio-header">
    <div id="portfolio-search-box">
      <input id="portfolio-search-input" placeholder="Search for stock..." type="text" class="form-control" value="" autocomplete="off">
      <button type="button">
        <i class="fa fa-search"></i>
      </button>
    </div>
    <button type="button" id="portfolio-add" class="btn btn-primary btn-block"><span id="portfolio-add-first-label">ADD</span><span id="portfolio-add-second-label"> STOCK</span></button>
  </div>
  <ul id="portfolio-table-description">
    <li>
      <div>
        <span>Stock</span> <button id="portfolio-filter-stock" class="portfolio-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
    <li>
      <div>
        <span>Trades</span> <button id="portfolio-filter-trades" class="portfolio-filter"><span class="fa fa-sort"></span></button>
      </div>
    </li>
    <li>
      <div>
        <span>Settings</span>
      </div>
    </li>
  </ul>
  <ul id="portfolio-list">
  </ul>
</div>

<script>
  $(document).ready(function() {
    changeFilter(document.getElementById("portfolio-filter-stock"), Filter.Stock);
  });

  const Filter = {
    Stock: "stock",
    Trades: "trades"
  }

  var currentFilter;
  var currentFilterDirection;
  var currentFilterIcon;
  var currentNameFilter = "";

  function sort(field) {
    return function(first, second) {
      return (first[field] > second[field]) - (first[field] < second[field])
    };
  }

  function displayPortfolio() {
    let request = new HttpRequest("http://lukasbreuer.de:8080/portfolio", "GET", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}]);
    request.send(function (status, responseText) {
      fetchTrades(JSON.parse(responseText).portfolio);
    });
  }

  function fetchTrades(portfolio) {
    var trades = new Map();
    for (let i = 0; i < portfolio.length; i++) {
      let stock = portfolio[i].stock;
      let request = new HttpRequest("http://lukasbreuer.de:8080/trades", "POST", [{key: "Content-Type", value: "application/json"},
        {key: "API-KEY", value: Cookie.find("token")}], {stock: stock});
      request.send(function (status, responseText) {
        var tradeCount = JSON.parse(responseText).trades.length;
        trades.set(stock, tradeCount);
        portfolio[i].trades = tradeCount;
        if (trades.size == portfolio.length) {
          completePortfolioDisplay(portfolio);
        }
      });
    }
  }

  function completePortfolioDisplay(stocks) {
    var portfolio = stocks.sort(sort(currentFilter));
    if (currentFilterDirection) {
      portfolio.reverse();
    }
    document.getElementById("portfolio-list").innerHTML = "";
    for (var i = 0; i < portfolio.length; i++) {
      var entry = portfolio[i];
      if(!entry.stock.toLowerCase().startsWith(currentNameFilter.toLowerCase())) {
          continue;
      }
      displayStock(entry.id, entry.stock, entry.trades);
    }
  }

  function displayStock(stockId, stockName, stockTrades) {
    var entry = document.createElement('li');
    entry.addEventListener("click", event => {
      var targetClasses = event.target.classList;
      if (targetClasses.contains("portfolio-settings") || targetClasses.contains("fa")) {
        return;
      }
      window.sessionStorage.setItem("stock", stockName);
      window.location.href = "/?page=stock/";
    });
    var stockDiv = document.createElement("div");
    var stockSpan = document.createElement("span");
    stockSpan.innerText = stockName;
    stockSpan.classList.add("portfolio-list-stock");
    stockDiv.appendChild(stockSpan);
    entry.appendChild(stockDiv);
    var tradesDiv = document.createElement("div");
    var tradesSpan = document.createElement("span");
    tradesSpan.innerText = stockTrades;
    tradesSpan.classList.add("portfolio-list-trades");
    tradesDiv.appendChild(tradesSpan);
    entry.appendChild(tradesDiv);
    var settingsDiv = document.createElement("div");
    var settingsButton = document.createElement("button");
    settingsButton.classList.add("portfolio-settings");
    settingsButton.addEventListener("click", event => {
      openStockSettings(stockId, stockName);
    });
    var settingsI = document.createElement("I");
    settingsI.classList.add("fa", "fa-gear");
    settingsButton.appendChild(settingsI);
    settingsDiv.appendChild(settingsButton);
    entry.appendChild(settingsDiv);
    document.getElementById("portfolio-list").appendChild(entry);
  }

  var filerElements = document.getElementsByClassName("portfolio-filter");
  for (var i = 0; i < filerElements.length; i++) {
    let element = filerElements[i];
    element.addEventListener("click", event => {
      changeFilter(element, element.id.replace("portfolio-filter-", ""));
    });
  }

  function changeFilter(element, filter) {
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
      currentFilterDirection = filter === Filter.Stock;
      currentFilterIcon = element.firstChild;
      changeFilter(element, filter);
      return;
    }
    displayPortfolio();
  }

  document.getElementById("portfolio-search-input").oninput = function(event) {
    currentNameFilter = event.target.value;
    displayPortfolio();
  };

  document.getElementById("portfolio-add").addEventListener("click", event => {
    const div = document.createElement("div");
    fetch('parts/portfolio/portfolio_add.html')
      .then(response => response.text())
      .then(text => $(div).html(text));
    swal({
      title: "Stock",
      content: div,
      buttons: true
    }).then(shouldAdd => {
      if (shouldAdd) {
        if ($("#portfolio-stock").val() == "") {
          swal({
            title: "Oops...",
            text: "Please add a stock name before you create the entry!",
            icon: "error"
          });
          return;
        }
        let request = new HttpRequest("http://lukasbreuer.de:8080/portfolio/add", "POST", [{key: "Content-Type", value: "application/json"},
          {key: "API-KEY", value: Cookie.find("token")}], {stockName: $("#portfolio-stock").val()});
        request.send(function (status, responseText) {swal("Added!", "You have successfully added that stock to the portfolio!", "success").then(success => displayPortfolio());});
      }
    });
  });

  function openStockSettings(stockId, stockName) {
    window.sessionStorage.setItem("portfolioStock", stockName);
    const div = document.createElement("div");
    fetch('parts/portfolio/portfolio_add.html')
      .then(response => response.text())
      .then(text => $(div).html(text));
    swal({
      title: "Stock",
      content: div,
      buttons: {
        cancel: "Cancel",
        danger: "Delete",
        confirm: "OK"
      },
    }).then(result => {
      if (result === "danger") {
        swal({
          title: "Delete Stock?",
          text: "Are you sure that you want to delete that stock?",
          icon: "warning",
          dangerMode: true,
          buttons: true,
        }).then(willDelete => {
          if (willDelete) {
            let request = new HttpRequest("http://lukasbreuer.de:8080/portfolio/remove", "POST", [{key: "Content-Type", value: "application/json"},
              {key: "API-KEY", value: Cookie.find("token")}], {stockId: stockId});
            request.send(function (status, responseText) {});
            swal("Deleted!", "That stock has been successfully deleted!", "success").then(success => location.reload());
          }
        });
      }
      if (result === true) {
        if ($("#portfolio-stock").val() == "") {
          swal({
            title: "Oops...",
            text: "Please add a stock name before you create the entry!",
            icon: "error"
          });
          return;
        }
        let request = new HttpRequest("http://lukasbreuer.de:8080/portfolio/update", "POST", [{key: "Content-Type", value: "application/json"},
          {key: "API-KEY", value: Cookie.find("token")}], {stockId: stockId, stockName: $("#portfolio-stock").val()});
        request.send(function (status, responseText) {swal("Adjusted!", "You have successfully adjusted that stock!", "success").then(success => displayPortfolio());});
      }
    });
  }
</script>