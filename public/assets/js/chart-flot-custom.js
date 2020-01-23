// CHART AREA
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Visits",
      "color": "#f55f60",
      "data": [
        ["Mon", 60],
        ["Tue", 84],
        ["Wed", 82],
        ["Thr", 98],
        ["Fri", 79],
        ["Sat", 72]
      ]
    }, {
      "label": "Likes",
      "color": "#ddd",
      "data": [
         ["Mon", 13],
        ["Tue", 34],
        ["Wed", 22],
        ["Thr", 48],
        ["Fri", 39],
        ["Sat", 32]
      ]
    }];

    var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.8
                        },
                        points: {
                            show: true,
                            radius: 4
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
                        tickColor: '#eee',
                        // position: 'right' or 'left'
                        tickFormatter: function (v) {
                            return v + ' Likes';
                        }
                    },
                    shadowSize: 0
                };

    var chart = $('#chart-area');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);
// CHART SPLINE
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

// CHART BAR
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Sales",
      "color": "#f55f60",
      "data": [
        ["Jan", 27],
        ["Feb", 82],
        ["Mar", 56],
        ["Apr", 14],
        ["May", 28],
        ["Jun", 77],
        ["Jul", 23],
        ["Aug", 49],
        ["Sep", 81],
        ["Oct", 20]
      ]
    }];

    var options = {
                    series: {
                        bars: {
                            align: 'center',
                            lineWidth: 0,
                            show: true,
                            barWidth: 0.6,
                            fill: 0.9
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
                        // position: 'right' or 'left'
                        tickColor: '#eee'
                    },
                    shadowSize: 0
                };

    var chart = $('#chart-bar');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);


// CHART BAR STACKED
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Likes",
      "color": "#8dc63f",
      "data": [
        ["Jan", 56],
        ["Feb", 141],
        ["Mar", 97],
        ["Apr", 44],
        ["May", 24],
        ["Jun", 85],
        ["Jul", 94],
        ["Aug", 78],
        ["Sep", 52],
        ["Oct", 117],
        ["Nov", 90],
        ["Dec", 62]
      ]
    }, {
      "label": "Folowers",
      "color": "#f55f60",
      "data": [
        ["Jan", 69],
        ["Feb", 125],
        ["Mar", 14],
        ["Apr", 100],
        ["May", 100],
        ["Jun", 62],
        ["Jul", 115],
        ["Aug", 22],
        ["Sep", 104],
        ["Oct", 72],
        ["Nov", 72],
        ["Dec", 31]
      ]
    }];


    var options = {
                    series: {
                        stack: true,
                        bars: {
                            align: 'center',
                            lineWidth: 0,
                            show: true,
                            barWidth: 0.6,
                            fill: 0.9
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
                        // position: 'right' or 'left'
                        tickColor: '#eee'
                    },
                    shadowSize: 0
                };

    var chart = $('#bar-stacked');
    if(chart.length)
      $.plot(chart, data, options);
  });

})(window, document, window.jQuery);

// CHART DONUT
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [ { "color" : "#8dc63f",
        "data" : 60,
        "label" : "Coffee"
      },
      { "color" : "#cc3f44",
        "data" : 90,
        "label" : "CSS"
      },
      { "color" : "#4285f4",
        "data" : 50,
        "label" : "SASS"
      },
      { "color" : "#f55f60",
        "data" : 80,
        "label" : "BOWER"
      },
      { "color" : "#ffba00",
        "data" : 116,
        "label" : "Bootstrap"
      }
    ];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0.5 // This makes the donut shape
                        }
                    }
                };

    var chart = $('#chart-donut');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);

// CHART LINE
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
        "label": "Chrome",
        "color": "#4285f4",
        "data": [
            ["Jan", 188],
            ["Feb", 183],
            ["Mar", 215],
            ["Apr", 179],
            ["May", 220],
            ["Jun", 194],
            ["Jul", 234],
            ["Aug", 184],
            ["Sep", 194]
        ]
    }, {
        "label": "Mozilla",
        "color": "#8dc63f",
        "data": [
            ["Jan", 222],
            ["Feb", 116],
            ["Mar", 136],
            ["Apr", 119],
            ["May", 98],
            ["Jun", 133],
            ["Jul", 148],
            ["Aug", 101],
            ["Sep", 109]
        ]
    }, {
        "label": "Safari",
        "color": "#f55f60",
        "data": [
            ["Jan", 121],
            ["Feb", 17],
            ["Mar", 73],
            ["Apr", 100],
            ["May", 02],
            ["Jun", 75],
            ["Jul", 07],
            ["Aug", 22],
            ["Sep", 76]
        ]
    }];

    var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.01
                        },
                        points: {
                            show: true,
                            radius: 4
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
                        tickColor: '#eee',
                        mode: 'categories'
                    },
                    yaxis: {
                        // position: 'right' or 'left'
                        tickColor: '#eee'
                    },
                    shadowSize: 0
                };

    var chart = $('#chart-line');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);


// CHART PIE
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "jQuery",
      "color": "#cc3f44",
      "data": 30
    }, {
      "label": "CSS",
      "color": "#ffba00",
      "data": 40
    }, {
      "label": "SASS",
      "color": "#f55f60",
      "data": 90
    }, {
      "label": "Bootstrap",
      "color": "#8dc63f",
      "data": 75
    }, {
      "label": "Bower",
      "color": "#4285f4",
      "data": 120
    }];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0,
                            label: {
                                show: true,
                                radius: 0.8,
                                formatter: function (label, series) {
                                    return '<div class="flot-pie-label">' +
                                    //label + ' : ' +
                                    Math.round(series.percent) +
                                    '%</div>';
                                },
                                background: {
                                    opacity: 0.8,
                                    color: '#222'
                                }
                            }
                        }
                    }
                };

    var chart = $('#pie-chart');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);