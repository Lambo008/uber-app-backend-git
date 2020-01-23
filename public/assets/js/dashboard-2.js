$(function () {
    "use strict";
    Morris.Bar({
        element: 'morris-bar-chart',
        data: [
            {y: '2006', a: 100, b: 90},
            {y: '2007', a: 75, b: 65},
            {y: '2008', a: 50, b: 40},
            {y: '2009', a: 75, b: 65},
            {y: '2010', a: 50, b: 40},
            {y: '2011', a: 75, b: 65},
            {y: '2012', a: 100, b: 90}
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        barColors: ['#f55f60', '#2196F3'],
        hideHover: 'auto',
        resize: true
    });
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
            '#ddd',
            '#f55f60',
            '#2196F3'
        ]
    });
    $("#sparkline-line1").sparkline([34, 43, 43, 35, 44, 65, 44, 52, 25], {
        type: 'line',
        lineColor: '#f55f60',
        fillColor: '#f5f5f5',
        width: 100,
        height: 40
    });
    $("#sparkline-line2").sparkline([34, 23, 43, 32, 44, 32, 44, 52, 43], {
        type: 'line',
        lineColor: '#4CAF50',
        fillColor: '#f5f5f5',
        width: 100,
        height: 40
    });
    $("#sparkline-line3").sparkline([34, 43, 43, 23, 44, 32, 44, 52, 51], {
        type: 'line',
        lineColor: '#F44336',
        fillColor: '#f5f5f5',
        width: 100,
        height: 40
    });
    $("#sparkline-line4").sparkline([34, 43, 65, 35, 44, 32, 44, 52, 45], {
        type: 'line',
        lineColor: '#3f51b5',
        fillColor: '#f5f5f5',
        width: 100,
        height: 40
    });


    jQuery('#world-map-markers').vectorMap(
            {
                map: 'world_mill_en',
                backgroundColor: '#fff',
                borderColor: '#818181',
                borderOpacity: 0.25,
                borderWidth: 1,
                color: '#f4f3f0',
                regionStyle: {
                    initial: {
                        fill: '#ddd'
                    }
                },
                markerStyle: {
                    initial: {
                        r: 9,
                        'fill': '#fff',
                        'fill-opacity': 1,
                        'stroke': '#000',
                        'stroke-width': 5,
                        'stroke-opacity': 0.4
                    }
                },
                enableZoom: true,
                hoverColor: '#c9dfaf',
                markers: [{
                        latLng: [21.00, 78.00],
                        name: 'Marker title'

                    }],
                hoverOpacity: null,
                normalizeFunction: 'linear',
                scaleColors: ['#b6d6ff', '#005ace'],
                selectedColor: '#c9dfaf',
                selectedRegions: [],
                showTooltip: true,
                onRegionClick: function (element, code, region)
                {
                    var message = 'You clicked "'
                            + region
                            + '" which has the code: '
                            + code.toUpperCase();

                    alert(message);
                }
            });
});

