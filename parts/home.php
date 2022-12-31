<canvas id="myChart" style="width:100%;max-width:700px"></canvas>

<script>
var xValues = [100,200,300,400,500,600,700,800,900,1000];

new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      label: "Data 1",
      data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478],
      borderColor: "#4c5192",
      fill: false
    },{
      label: "Data 2",
      data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      borderColor: "green",
      fill: false
    },{
      label: "Data 3",
      data: [300,700,2000,5000,6000,4000,2000,1000,200,100],
      borderColor: "blue",
      fill: false
    }]
  },
  options: {
    scales: {
      xAxes: [{gridLines: { color: "#131c2b" }}],
      yAxes: [{gridLines: { color: "#131c2b" }}]
    }
  }
});
</script>