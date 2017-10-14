$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

Chart.defaults.global.legend.display = false;
Chart.defaults.global.defaultFontFamily = "'Montserrat', 'Arial', 'sans-serif'";

var DATE_FORMAT = "YYYY-MM-DD";
var DATE_MAX = "3000-01-01";
var DATE_MIN = "1970-01-01";
var ctx = document.getElementById("graph").getContext("2d");
var view;
var chart;

$(document).ready(function() {

  selectpickerChanged();
  $(".selectpicker").on("change", selectpickerChanged);
});

function selectpickerChanged() {

  var query = $(".selectpicker").selectpicker("val").split("-");

  var key   = query[0];
  var value = query[1];

  switch(key) {

    case "limit":

      refreshChart(value, {
        limit: value
      });
      break;

    case "last":

      // get date of today in format 'YYYY-MM-DD' and date of today - 'value' days
      refreshChart(value, {
        from: moment().subtract(value, 'days').format(DATE_FORMAT),
        to:   moment().format(DATE_FORMAT)
      });
      break;

    case "all":

      refreshChart(-1, {
        from: DATE_MIN,
        to:   DATE_MAX
      });
      break;
  }
}

function refreshChart(days, parameters) {

  $.post("/stats/"+view, parameters, function(data, status) {

    if(status == "success") {

      console.log(data);

      data = prepareData(data, parameters, days);

      if(chart == null) {   // create new chart

        chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: data.labels,
            datasets: data.datasets
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              }
          }
        });

      } else {              // update existing chart

        chart.data.labels = data.lables;
        chart.data.datasets = data.datasets;
        chart.update();
      }

      console.log(data);

    } else {

      console.error("Failed to load data. Status: " + status);
    }
  });
}

function prepareData(rawData, parameters, days) {

  var labels = extractLabels(rawData, parameters, days);

  console.log(labels);

  data = {
    labels: labels,
    datasets: []
  };

  if(typeof rawData.keys == "undefined") {

    data.datasets.push(initDatatset(""));

  } else {

    for(var i = 0; i < rawData.keys.length; i++) {

      data.datasets.push(initDatatset(rawData.keys[i]));
    }
  }

  for(var key in rawData.data) {

    if(typeof rawData.data[key] == "object") {

      for(var i = 0; i < rawData.keys.length; i++) {

        data.datasets[i].data.push(rawData.data[key][rawData.keys[i]]);
      }

    } else if(typeof rawData.data[key] == "number") {

      data.datasets[0].data.push(rawData.data[key]);

    }
  }

  return data;
}

function extractLabels(rawData, parameters, days) {

  var lowerBound = parameters.from;
  var start = moment(lowerBound);

  if(days === -1) {

    start = moment(lowestDate);
    end = moment(highestDate);
    days = end - start;
  }

  var labels = [start.format(DATE_FORMAT)];

  for(var i = 0; i < days; i++) {

    labels.push(start.add(1, 'days').format(DATE_FORMAT));
  }

  return labels;
}

function initDatatset(label) {

  return {
    label: label,
    data: [],
    lineTension: 0.3,
    backgroundColor: 'rgba(139, 195, 74, 0.3)',
    borderColor: 'rgba(139, 195, 74, 1)',
    borderWidth: 1
  };
}
