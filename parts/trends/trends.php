<div class="container pt-2">
  <div class="row justify-content-between">
    <div class="col dashboard-column">
      <div class="dashboard-table-content">
        <div class="table-responsive">
          <table id="example" class="table" style="width:100%">
            <thead>
            <tr>
              <th>Name</th>
              <th class="nosort">Position</th>
              <th class="nosort">Office</th>
              <th class="nosort">Salary</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>Tiger Nixon</td>
              <td>System Architect</td>
              <td>Edinburgh</td>
              <td>$320,800</td>
            </tr>
            <tr>
              <td>Garrett Winters</td>
              <td>Accountant</td>
              <td>Tokyo</td>
              <td>$170,750</td>
            </tr>
            <tr>
              <td>Ashton Cox</td>
              <td>Junior Technical Author</td>
              <td>San Francisco</td>
              <td>$86,000</td>
            </tr>
            <tr>
              <td>Cedric Kelly</td>
              <td>Senior Javascript Developer</td>
              <td>Edinburgh</td>
              <td>$433,060</td>
            </tr>
            <tr>
              <td>Airi Satou</td>
              <td>Accountant</td>
              <td>Tokyo</td>
              <td>$162,700</td>
            </tr>
            <tr>
              <td>Airi Satou</td>
              <td>Accountant</td>
              <td>Tokyo</td>
              <td>$162,700</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#example').DataTable({searching: false, info: false, lengthChange: false, pageLength: 5, asStripeClasses: [],
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