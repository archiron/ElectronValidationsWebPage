window.chartColors = {
    red: 'rgb(255, 0, 0)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(0, 0, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(231,233,237)',
    black: 'rgb(0,0,0)',
    white: 'rgb(255,255,255)',
};

var relList = []
var valuesSelected = []
var allValues = []
$.each(all_courbes, function(index, value) {
    //console.log(index + ' ==: ' + value[0] + '-' + value[1]);
    if (index != ref_reference) {
        relList.push(index)
        allValues.push(value[0])
        valuesSelected.push(value[1])
    }
    });
//console.log(relList)
//console.log(valuesSelected)
//console.log(allValues)
    
const plugin = {
    id: 'customCanvasBackgroundColor',
    beforeDraw: (chart, args, options) => {
      const {ctx} = chart;
      ctx.save();
      ctx.globalCompositeOperation = 'destination-over';
      ctx.fillStyle = options.color || window.chartColors.white // '#99ffff';
      ctx.fillRect(0, 0, chart.width, chart.height);
      ctx.restore();
    }
  };

new Chart(document.getElementById("myChart"), {
    type: 'line',
    data: {
        labels: relList,
        datasets: [
        {
            label: "cumulated max diff",
            backgroundColor: window.chartColors.red, //["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            borderColor: window.chartColors.red,
          data: valuesSelected,
          showLine: false,
            pointStyle: 'star',
            pointRadius: 6,
        },
        {
            label: 'All curves',
            backgroundColor: "#3e95cd", //window.chartColors.red,
            borderColor: "#3e95cd", //window.chartColors.red,
            data: allValues,
            showLine: false,
            pointStyle: 'crossRot',
            pointRadius: 6,
        },
    ]
    },
    //config: {    
        options: {
            //legend: { display: true },
            plugins: {
                legend: {position: 'top',},
                title: {
                    display: true, 
                    text: 'cumulated max diff with ' + ref_reference + ' reference',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 0,
                    min: 0,
                    //max: 1,
                },
            },
        },
    //},
    plugins: [plugin],
});/**/

Chart.defaults.scales.linear.min = 0;