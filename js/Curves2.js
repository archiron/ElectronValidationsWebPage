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
    if (index != ref_reference) {
        relList.push(index)
        allValues.push(value[0])
        valuesSelected.push(value[1])
    }
    });
    
const plugin = {
    id: 'KS_evaluation',
    beforeDraw(chart, args, options) {
      const {ctx} = chart;

      ctx.save();
      ctx.globalCompositeOperation = 'destination-over';
      ctx.fillStyle = options.color || window.chartColors.white // '#99ffff';
      ctx.fillRect(0, 10, chart.width, chart.height);
      ctx.restore(); // <-- important pour revenir au mode normal
      
    },
    afterLayout(chart) {
      // On stocke la position de la légende pour y placer le titre juste au-dessus
      const legend = chart.legend?.top ?? chart.chartArea.top;
      chart.$customTitleY = legend - 25; // position verticale du texte
    },
    afterDraw(chart) {
      const {ctx, chartArea: {left, right}} = chart;
      const y = chart.$KS_evaluation || 20;
      const titre = "cumulated max diff with reference : "
      const release = ref_reference

      ctx.save();
      ctx.font = "bold 18px Arial";
      ctx.textBaseline = "top";

      const titreWidth = ctx.measureText(titre).width; // Mesurer la largeur du premier texte
      const totalWidth = titreWidth + ctx.measureText(release).width;
      const x = left + (right - left) / 2 - totalWidth / 2; // centré globalement

      ctx.fillStyle = "black";
      ctx.fillText(titre, x, y);
      ctx.fillStyle = "blue";
      ctx.fillText(release, x + titreWidth, y);

      ctx.restore();
    }
  };

new Chart(document.getElementById("myChart").getContext('2d'), {
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
        options: {
            //legend: { display: true },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                },
                layout: {
                    padding: {top: 40},
                },
                /*title: {
                    display: true, 
                    text: 'cumulated max diff with reference : ' + ref_reference,
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                },*/
                /*annotation: {
                    annotations: {
                        label1: {
                            type: 'label',
                            xValue: 0.,
                            yValue: 1.,
                            backgroundColor: 'rgba(0,255,0)',
                            content: ['Toto'],
                            font: {
                                size: 18,
                                weight: 'bold',
                            },
                        },
                    },
                },*/
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMin: 0,
                    min: 0,
                    //max: 1,
                    title: {
                        display:true,
                        text: 'Max cumulated diff.',
                        font: {
                            weight: 'bold',
                        },
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Releases',
                        font: {
                            weight: 'bold',
                        },
                    }
                },
            },
        },
    plugins: [plugin],
});/**/

Chart.defaults.scales.linear.min = 0;