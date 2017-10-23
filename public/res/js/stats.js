$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

Chart.defaults.global.legend.display = false;
Chart.defaults.global.defaultFontFamily = "'Montserrat', 'Arial', 'sans-serif'";

var DATE_FORMAT = "YYYY-MM-DD";
var DATE_MAX    = "3000-01-01";
var DATE_MIN    = "1970-01-01";
var statsURI    = "/stats/";
var graphId     = "graph";
var ctx         = document.getElementById(graphId).getContext("2d");
var view;
var chart;

$(document).ready(function() {

  selectpickerChanged();
  $(".selectpicker").on("change", selectpickerChanged);
});

/**
  * called when the selectpicker changes
  * creates/updates chart
  *
  * @return void
  */
function selectpickerChanged() {

  var query = $(".selectpicker").selectpicker("val").split("-");

  var selector  = query[0];
  if(query.length === 2) var value = query[1];

  switch(selector) {

    case "limit":

      updateChart({
        limit:    value,
        selector: selector,
      });
      break;

    case "last":

      // get date of today in format 'YYYY-MM-DD' and date of today - 'value' days
      updateChart({
        from:     moment().subtract(value, 'days').format(DATE_FORMAT),
        to:       moment().format(DATE_FORMAT),
        days:     value,
        selector: selector,
      });
      break;

    case "all":

      updateChart({
        from:     DATE_MIN,
        to:       DATE_MAX,
        selector: selector,
        // no limit set as parameter => server sets no limit
      });
      break;
    }
}

/**
  * retrieves data from server and creates/updates chart
  *
  * @param JSON parameters
  * @return void
  */
function updateChart(parameters) {

  console.log(parameters);

  $.post(statsURI + view, parameters, function(data, status) {

    if(status == "success") {

      console.log(data);

      data = prepareData(parameters, data);

      console.log(data);


      if(chart == null) {

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

      } else {

        // update charts
        chart.data.labels   = data.labels;
        chart.data.datasets = data.datasets;
        chart.update();
      }

    } else {

      console.error("Failed to load data. Status: " + status);
    }
  });
}

/**
  * prepares data (creates labels and datasets)
  *
  * @param JSON parameters
  * @param JSON rawData
  * @return JSON (prepared) data
  */
function prepareData(parameters, rawData) {

  var labels = extractLabels(parameters, rawData);

  var data = {
    labels: labels,
    datasets: []
  };

  var keys = [];
  // init dataset objects
  if(is_set(rawData.keys)) { // create named datasets

    for(var i = 0; i < rawData.keys.length; i++) {

      keys.push(rawData.keys[i]);
      data.datasets.push(initDataset(rawData.keys[i]));
    }

  } else {  // create one anonymous dataset

    keys.push("value");
    data.datasets.push(initDataset("value"));
  }

  if(rawData.data.length == 0) return data; // no data needs to be added

  // add data
  var dataIndex = 0;
  var flag = false;
  for(var i = 0; i < labels.length; i++) {

    for(var k = 0; k < keys.length; k++) {

      var key = keys[k];

      if(dataIndex < rawData.data.length && labels[i] == rawData.data[dataIndex].date) {

        data.datasets[k].data.push(rawData.data[dataIndex][key]);
        flag = true;

      } else {

        data.datasets[k].data.push(0);
      }
    }

    if(flag) {
      dataIndex++;
      flag = false;
    }
  }

  return data;
}

/**
  * extract labels from data; "fill" missing dates if necessary
  *
  * @param JSON parameters
  * @param JSON rawData
  * @return array labels
  */
function extractLabels(parameters, rawData) {

  var labels = [];

  switch(parameters.selector) {

    case "limit":   // use labels from data

      for(var i = 0; i < rawData.data.length; i++) {

        labels.push(rawData.data[i].date);
      }
      break;

    case "last":    // fill missing gaps

      var start  = moment(parameters.from);
      var end    = moment(parameters.to);

      while(start.format(DATE_FORMAT) !== end.format(DATE_FORMAT)) {

        labels.push(start.add(1, 'days').format(DATE_FORMAT));
      }
      break;

    case "all":     // start at min date of data and fill gaps

      if(rawData.data.length === 0) return [];

      if(is_set(rawData.limit)) {

        for(var i = 0; i < rawData.data.length; i++) {

          labels.push(rawData.data[i].date);
        }

      } else {

        var start   = moment(rawData.data[0].date);
        var end     = moment();
        labels.push(start.format(DATE_FORMAT));

        while(start.format(DATE_FORMAT) !== end.format(DATE_FORMAT)) {

          labels.push(start.add(1, 'days').format(DATE_FORMAT));
        }

      }

      break;
  }

  return labels;
}

/**
  * returns empty dataset template
  *
  * @param string label
  * @return object dataset
  */
function initDataset(label) {

  // switch(view):
  return {
    label: label,
    data: [],
    lineTension: 0.3,
    backgroundColor: 'rgba(139, 195, 74, 0.3)',
    borderColor: 'rgba(139, 195, 74, 1)',
    borderWidth: 1
  };
}

/**
  * helper function to check if a property is set
  *
  * @param property
  * @return boolean
  */
function is_set(property) {

  return (typeof property !== "undefined");
}
