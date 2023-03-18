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
          <tbody>
          <tr>
            <td>GOOGL</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.google.com&sz=24'/></td>
            <td>Alphabet Inc.</td>
            <td>Internet Content & Information</td>
            <td>$79.29</td>
            <td><span class="trend-stock-status trend-stock-status-integrated">Already integrated</span></td>
            <td>
              <span id="dropdown-menu-button" data-bs-toggle="dropdown" aria-expanded="false" class="fa-solid fa-ellipsis-vertical trend-stock-options-icon"></span>
              <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdown-menu-button">
                <li><a class="dropdown-item" href="#">Investigate</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Integrate</a></li>
                <li><a class="dropdown-item" href="#">Remove</a></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td>TSLA</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.tesla.com&sz=24'/></td>
            <td>Tesla, Inc</td>
            <td>Auto Manufacturers</td>
            <td>$42.63</td>
            <td><span class="trend-stock-status trend-stock-status-pending">To be recorded</span></td>
            <td><span class="fa-solid fa-ellipsis-vertical"></span></td>
          </tr>
          <!--
          <tr>
            <td>Garrett Winters</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.eversource.com&sz=24' /></td>
          </tr>
          <tr>
            <td>Ashton Cox</td>
            <td>Junior Technical Author</td>
            <td>San Francisco</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.ford.com&sz=24' /></td>
          </tr>
          <tr>
            <td>Cedric Kelly</td>
            <td>Senior Javascript</td>
            <td>Edinburgh</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.tesla.com&sz=24' /></td>
          </tr>
          <tr>
            <td>Airi Satou</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.google.com&sz=24' /></td>
          </tr>
          <tr>
            <td>Airi Satou</td>
            <td>Accountant</td>
            <td>Tokyo</td>
            <td><img height="24" width="24" src='https://www.google.com/s2/favicons?domain=www.github.com&sz=24' /></td>
          </tr>
          -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
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
      }]});
  });
</script>