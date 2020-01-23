    $(function () {
   $("#sparkline-line1").sparkline([34, 43, 43, 35, 44, 65, 44, 52, 25], {
            type: 'line',
            lineColor: '#f55f60',
            fillColor: '#f5f5f5',
            width:100,
            height:40
        });
         $("#sparkline-line2").sparkline([34, 23, 43, 32, 44, 32, 44, 52, 43], {
            type: 'line',
            lineColor: '#4CAF50',
            fillColor: '#f5f5f5',
            width:100,
            height:40
        });
         $("#sparkline-line3").sparkline([34, 43, 43, 23, 44, 32, 44, 52, 51], {
            type: 'line',
            lineColor: '#F44336',
            fillColor: '#f5f5f5',
            width:100,
            height:40
        });
         $("#sparkline-line4").sparkline([34, 43, 65, 35, 44, 32, 44, 52, 45], {
            type: 'line',
            lineColor: '#3f51b5',
            fillColor: '#f5f5f5',
            width:100,
            height:40
        });
    });

