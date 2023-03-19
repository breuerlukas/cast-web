<div class="container pt-2">
  <div class="row justify-content-between">
    <div class="col">
      <div class="table-responsive">
        <table id="example" class="table dt-responsive nowrap">
          <thead>
          <tr>
            <th data-priority="0">Symbol</th>
            <th data-priority="0.5" class="nosort">Icon</th>
            <th data-priority="0.3">Company</th>
            <th data-priority="0.6">Industry</th>
            <th data-priority="0.2">Price</th>
            <th data-priority="0.4">Status</th>
            <th data-priority="0.1" class="nosort"></th>
          </tr>
          </thead>
          <tbody id="table-body">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    updateTrends();
  });

  function updateTrends() {
    fetchPortfolioData(function (portfolio) {
      fetchTrendData(function (trends) {
        displayTrends(trends, portfolio);
        initializeDatatable();
      });
    });
  }

  function initializeDatatable() {
    $('#example').DataTable({responsive: true, colReorder: true, searching: false, info: false, lengthChange: false, pageLength: 5, asStripeClasses: [],
      language: {
        paginate: {
          previous: "<",
          next: ">"
        }
      },
      columnDefs: [{
        orderable: false,
        targets: ["nosort"]
      }]
    });
  }

  function fetchPortfolioData(callback) {
    let request = new HttpRequest("https://lukasbreuer.de:8080/portfolio", "GET", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}]);
    request.send(function (status, responseText) {
      callback(JSON.parse(responseText).portfolio);
    });
  }

  function fetchTrendData(callback) {
    let request = new HttpRequest("https://lukasbreuer.de:8080/trends", "GET", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}]);
    request.send(function (status, responseText) {
      callback(JSON.parse(responseText).trends);
    });
  }

  function displayTrends(trends, portfolio) {
    for (var i = 0; i < trends.length; i++) {
      var trend = trends[i];
      displayStock(trend.symbol, trend.company, trend.industry, trend.price, trend.website,
        isAlreadyIntegrated(trend.symbol, portfolio));
    }
  }

  function isAlreadyIntegrated(symbol, portfolio) {
    for (var i = 0; i < portfolio.length; i++) {
      if (symbol === portfolio[i].stock) {
        return true
      }
    }
    return false;
  }

  function displayStock(symbol, company, industry, price, website, alreadyIntegrated) {
    var table = document.getElementById("table-body");
    var entryElement = document.createElement("tr");
    appendCell(entryElement, "td", symbol);
    appendCell(entryElement, "td", "").appendChild(createIconImage(website));
    appendCell(entryElement, "td", company);
    appendCell(entryElement, "td", industry.replace("—", " - "));
    appendCell(entryElement, "td", parseFloat(price).toFixed(2));
    appendCell(entryElement, "td", "").appendChild(createStatusSpan(alreadyIntegrated));
    var dropdownElement = appendCell(entryElement, "td", "");
    dropdownElement.appendChild(createDropdownButton());
    dropdownElement.appendChild(createDropdownList(symbol));
    table.appendChild(entryElement);
  }

  function createIconImage(website) {
    var iconImage = document.createElement("img");
    iconImage.width = 32;
    iconImage.height = 32;
    iconImage.src = "https://www.google.com/s2/favicons?domain=" + website + "&sz=64";
    return iconImage;
  }

  function createStatusSpan(alreadyIntegrated) {
    var statusSpan = document.createElement("span");
    if (alreadyIntegrated) {
      statusSpan.innerText = "Already integrated";
      statusSpan.classList.add("trend-stock-status-integrated");
    } else {
      statusSpan.innerText = "To be recorded";
      statusSpan.classList.add("trend-stock-status-pending");
    }
    statusSpan.classList.add("trend-stock-status");
    return statusSpan;
  }

  function createDropdownButton() {
    var dropdownSpan = document.createElement("span");
    dropdownSpan.id = "dropdown-menu-button";
    dropdownSpan.setAttribute("data-bs-toggle", "dropdown");
    dropdownSpan.ariaExpanded = false;
    dropdownSpan.classList.add("fa-solid", "fa-ellipsis-vertical", "trend-stock-options-icon");
    return dropdownSpan;
  }

  function createDropdownList(symbol) {
    var dropdownList = document.createElement("ul");
    dropdownList.classList.add("dropdown-menu", "dropdown-menu-dark");
    dropdownList.setAttribute("aria-labelledby", "dropdown-menu-button");
    appendDropdownItem(dropdownList, "Investigate", event => investigateSymbol(symbol));
    appendDropdownDivider(dropdownList);
    appendDropdownItem(dropdownList, "Integrate", event => integrateSymbol(symbol));
    appendDropdownItem(dropdownList, "Remove", event => removeSymbol(symbol));
    return dropdownList;
  }

  function investigateSymbol(symbol) {
    window.location.href = "/stock?name=" + symbol
  }

  function integrateSymbol(symbol) {
    let request = new HttpRequest("https://lukasbreuer.de:8080/portfolio/add", "POST", [{key: "Content-Type", value: "application/json"},
      {key: "API-KEY", value: Cookie.find("token")}], {stockName: symbol});
    request.send(function (status, responseText) {
      swal("Added!", "You have successfully added that stock to the portfolio!", "success");
    });
  }

  function removeSymbol(symbol) {
    //TODO: IMPLEMENT
  }

  function appendDropdownItem(dropdownList, name, clickEvent) {
    var dropdownItem = document.createElement("li");
    var dropdownItemContent = document.createElement("a");
    dropdownItemContent.classList.add("dropdown-item");
    dropdownItemContent.innerText = name;
    dropdownItemContent.addEventListener("click", clickEvent);
    dropdownItem.appendChild(dropdownItemContent);
    dropdownList.appendChild(dropdownItem);
    return dropdownItem;
  }

  function appendDropdownDivider(dropdownList) {
    var dropdownItem = document.createElement("li");
    var dropdownItemContent = document.createElement("hr");
    dropdownItemContent.classList.add("dropdown-divider");
    dropdownItem.appendChild(dropdownItemContent);
    dropdownList.appendChild(dropdownItem);
    return dropdownItem;
  }

  function appendCell(entryElement, elementType, information) {
    var element = document.createElement(elementType);
    element.innerText = information;
    entryElement.appendChild(element);
    return element;
  }
</script>