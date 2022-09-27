(function($) {
    'use strict';
    $(function() {
      if ($("#total-sales-chart").length) {
        var areaData = {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug","Sep", "Oct", "Nov", "Dec"],
          datasets: [
            {
              data: JSON.parse($("#total-sales-chart").attr("data-chart")),
              backgroundColor: [
                'rgba(61, 165, 244, .0)'
              ],
              borderColor: [
                'rgb(61, 165, 244)'
              ],
              borderWidth: 2,
              fill: 'origin',
              label: ""
            }
          ]
        };
        var areaOptions = {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            filler: {
              propagate: false
            }
          },
          scales: {
            xAxes: [{
              display: true,
              ticks: {
                display: true,
                padding: 20,
                fontColor:"#000",
                fontSize: 14
              },
              gridLines: {
                display: false,
                drawBorder: false,
                color: 'transparent',
                zeroLineColor: '#eeeeee'
              }
            }],
            yAxes: [{
              display: true,
              ticks: {
                display: true,
                autoSkip: false,
                maxRotation: 0,
                stepSize: 100,
                fontColor: "#000",
                fontSize: 14,
                padding: 18,
                stepSize: 100000,
                callback: function(value) {
                  var ranges = [
                      { divider: 1e6, suffix: 'M' },
                      { divider: 1e3, suffix: 'k' }
                  ];
                  function formatNumber(n) {
                      for (var i = 0; i < ranges.length; i++) {
                        if (n >= ranges[i].divider) {
                            return (n / ranges[i].divider).toString() + ranges[i].suffix;
                        }
                      }
                      return n;
                  }
                  return formatNumber(value);
                }
              },
              gridLines: {
                drawBorder: false
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            enabled: true
          },
          elements: {
            line: {
              tension: .37
            },
            point: {
              radius: 0
            }
          }
        }
        var revenueChartCanvas = $("#total-sales-chart").get(0).getContext("2d");
        var revenueChart = new Chart(revenueChartCanvas, {
          type: 'line',
          data: areaData,
          options: areaOptions
        });
      }
  
      if ($("#users-chart").length) {
        var areaData = {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug","Sep", "Oct", "Nov", "Dec"],
          datasets: [{
              data: JSON.parse($("#users-chart").attr("data-chart")),
              backgroundColor: [
                '#e0fff4'
              ],
              borderWidth: 3,
              borderColor: "#00c689",
              fill: 'origin',
              label: ""
            }
          ]
        };
        var areaOptions = {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            filler: {
              propagate: false
            }
          },
          scales: {
            xAxes: [{
              display: false,
              ticks: {
                display: true
              },
              gridLines: {
                display: false,
                drawBorder: false,
                color: 'transparent',
                zeroLineColor: '#eeeeee'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: true,
                autoSkip: false,
                maxRotation: 0,
                stepSize: 100,
                min: 0,
                max: parseInt($("#users-chart").attr("data-max"))
              },
              gridLines: {
                drawBorder: false
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            enabled: true
          },
          elements: {
            line: {
              tension: .35
            },
            point: {
              radius: 0
            }
          }
        }
        var salesChartCanvas = $("#users-chart").get(0).getContext("2d");
        var salesChart = new Chart(salesChartCanvas, {
          type: 'line',
          data: areaData,
          options: areaOptions
        });
      }
  
  
      if ($("#projects-chart").length) {
        var areaData = {
          labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug","Sep", "Oct", "Nov", "Dec"],
          datasets: [{
              data: JSON.parse($("#projects-chart").attr("data-chart")),
              backgroundColor: [
                '#e5f2ff'
              ],
              borderWidth: 3,
              borderColor: "#3da5f4",
              fill: 'origin',
              label: ""
            }
          ]
        };
        var areaOptions = {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            filler: {
              propagate: false
            }
          },
          scales: {
            xAxes: [{
              display: false,
              ticks: {
                display: true
              },
              gridLines: {
                display: false,
                drawBorder: false,
                color: 'transparent',
                zeroLineColor: '#eeeeee'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: true,
                autoSkip: false,
                maxRotation: 0,
                stepSize: 100,
                min: 0,
                max: parseInt($("#projects-chart").attr("data-max"))
              },
              gridLines: {
                drawBorder: false
              }
            }]
          },
          legend: {
            display: false
          },
          tooltips: {
            enabled: true
          },
          elements: {
            line: {
              tension: .05
            },
            point: {
              radius: 0
            }
          }
        }
        var salesChartCanvas = $("#projects-chart").get(0).getContext("2d");
        var salesChart = new Chart(salesChartCanvas, {
          type: 'line',
          data: areaData,
          options: areaOptions
        });
      }
  
      if ($('#offlineProgress').length) {
        var bar = new ProgressBar.Circle(offlineProgress, {
          color: '#000',
          // This has to be the same size as the maximum width to
          // prevent clipping
          strokeWidth: 6,
          trailWidth: 6,
          easing: 'easeInOut',
          duration: 1400,
          text: {
            autoStyleContainer: true,
            style : {
              color : "#fff",
              position: 'absolute',
              left: '40%',
              top: '50%'
            }
          },
          svgStyle: {
            width: '90%'
          },
          from: {
            color: '#fda006',
            width: 6
          },
          to: {
            color: '#fda006',
            width: 6
          },
          // Set default step function for all animate calls
          step: function(state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);
            circle.path.style.strokeDasharray = 300;
            circle.path.style.strokeDashoffset = parseInt($('#offlineProgress').attr("data-count"));

            var value = Math.ceil(circle.value() * 100);
            if (value === 0) {
              circle.setText('');
            } else {
              if(value < 0){
                value = 0
              }
              circle.setText(value+'%');
            }
    
          }
        });
    
        bar.text.style.fontSize = '1rem';
        bar.animate(.64); // Number from 0.0 to 1.0
      }
  
      if ($('#onlineProgress').length) {
        var bar = new ProgressBar.Circle(onlineProgress, {
          color: '#000',
          // This has to be the same size as the maximum width to
          // prevent clipping
          strokeWidth: 6,
          trailWidth: 6,
          easing: 'easeInOut',
          duration: 1400,
          text: {
            autoStyleContainer: true,
            style : {
              color : "#fff",
              position: 'absolute',
              left: '40%',
              top: '50%'
            }
          },
          svgStyle: {
            width: '90%'
          },
          from: {
            color: '#fda006',
            width: 6
          },
          to: {
            color: '#fda006',
            width: 6
          },
          // Set default step function for all animate calls
          step: function(state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);
            
            circle.path.style.strokeDasharray = 300;
            circle.path.style.strokeDashoffset = parseInt($('#onlineProgress').attr("data-count"));
            var value = Math.ceil(circle.value() * 100);
            if (value === 0) {
              circle.setText('');
            } else {
              if(value < 0){
                value = 0
              }
              circle.setText(value+'%');
            }
    
          }
        });
    
        bar.text.style.fontSize = '1rem';
        bar.animate(.84); // Number from 0.0 to 1.0
      }
    
    });
  })(jQuery);