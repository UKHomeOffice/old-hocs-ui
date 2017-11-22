$(function() {
    refreshCharts();
});

$(function() {
    $("#tabs").tabs({
        activate: function (event, ui) {
            refreshCharts();
        }
    });
});

function refreshCharts() {
    $('.chart').each(function() {
        var title = $(this).data('title');

        Highcharts.chart('chart'+title, {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: eval('chartText'+title),
                align: 'center',
                verticalAlign: 'top',
                y: 135
            },
            tooltip: eval('chartTooltip'+title),
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false
                    },
                    startAngle: 0,
                    endAngle: 360,
                    center: ['50%', '50%'],
                    borderWidth: 0
                }
            },
            series: [{
                type: 'pie',
                innerSize: '65%',
                data: eval('chartData'+title),
                states: {
                    hover: {
                        enabled: false
                    }
                }
            }],
            credits: {
                enabled: false
            },
            colors: eval('chartColor'+title)
        });
    });
}