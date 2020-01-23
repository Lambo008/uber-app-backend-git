// CHART BAR
// -----------------------------------




// // CHART SPLINE
// -----------------------------------
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Uniques",
      "color": "#ddd",
      "data": [
        ["Mar", 130],
        ["Apr", 115],
        ["May", 129],
        ["Jun", 133],
        ["Jul", 116],
        ["Aug", 86],
        ["Sep", 90]
      ]
    }, {
      "label": "Recurrent",
      "color": "#f55f60",
      "data": [
        ["Mar", 41],
        ["Apr", 62],
        ["May", 87],
        ["Jun", 94],
        ["Jul",56],
        ["Aug", 29],
        ["Sep", 75]
      ]
    }];




    var options = {
      series: {
          lines: {
              show: false
          },
          points: {
              show: true,
              radius: 4
          },
          splines: {
              show: true,
              tension: 0.4,
              lineWidth: 1,
              fill: 0.5
          }
      },
      grid: {
          borderColor: '#eee',
          borderWidth: 1,
          hoverable: true,
          backgroundColor: '#fcfcfc'
      },
      tooltip: true,
      tooltipOpts: {
          content: function (label, x, y) { return x + ' : ' + y; }
      },
      xaxis: {
          tickColor: '#fcfcfc',
          mode: 'categories'
      },
      yaxis: {
          min: 0,
          max: 150, // optional: use it for a clear represetation
          tickColor: '#eee',
          //position: 'right' or 'left',
          tickFormatter: function (v) {
              return v/* + ' visitors'*/;
          }
      },
      shadowSize: 0
    };

    var chart = $('#chart-spline');
    if(chart.length)
      $.plot(chart, data, options);
  });

})(window, document, window.jQuery);

$(function () {
    "use strict";

    /**
     * doughnut chart
     */
     Morris.Donut({
  element: 'morris-donut-chart',
  data: [
    {label: "Download Sales", value: 12},
    {label: "In-Store Sales", value: 30},
    {label: "Mail-Order Sales", value: 20}
  ],
  backgroundColor: '#fff',
  labelColor: '#666',
  colors: [
    '#999',
    '#f55f60',
    '#ddd'
  ]
});
    });
