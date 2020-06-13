<?php die("Access Denied"); ?>#x#a:2:{s:6:"result";a:2:{s:6:"report";a:0:{}s:2:"js";s:1411:"
  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Orders', 'Total Items sold', 'Revenue net'], ['2020-05-11', 0,0,0], ['2020-05-12', 0,0,0], ['2020-05-13', 0,0,0], ['2020-05-14', 0,0,0], ['2020-05-15', 0,0,0], ['2020-05-16', 0,0,0], ['2020-05-17', 0,0,0], ['2020-05-18', 0,0,0], ['2020-05-19', 0,0,0], ['2020-05-20', 0,0,0], ['2020-05-21', 0,0,0], ['2020-05-22', 0,0,0], ['2020-05-23', 0,0,0], ['2020-05-24', 0,0,0], ['2020-05-25', 0,0,0], ['2020-05-26', 0,0,0], ['2020-05-27', 0,0,0], ['2020-05-28', 0,0,0], ['2020-05-29', 0,0,0], ['2020-05-30', 0,0,0], ['2020-05-31', 0,0,0], ['2020-06-01', 0,0,0], ['2020-06-02', 0,0,0], ['2020-06-03', 0,0,0], ['2020-06-04', 0,0,0], ['2020-06-05', 0,0,0], ['2020-06-06', 0,0,0], ['2020-06-07', 0,0,0], ['2020-06-08', 0,0,0]  ]);
        var options = {
          title: 'Report for the period from Monday, 11 May 2020 to Tuesday, 09 June 2020',
            series: {0: {targetAxisIndex:0},
                   1:{targetAxisIndex:0},
                   2:{targetAxisIndex:1},
                  },
                  colors: ["#00A1DF", "#A4CA37","#E66A0A"],
        };

        var chart = new google.visualization.LineChart(document.getElementById('vm_stats_chart'));

        chart.draw(data, options);
      }
";}s:6:"output";s:0:"";}