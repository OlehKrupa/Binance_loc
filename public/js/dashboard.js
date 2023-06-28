/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/dashboard.js ***!
  \***********************************/
//main.js
$(document).ready(function () {
  var labelsSpan = document.getElementById('labels');
  var nameSpan = document.getElementById('name');
  var dataSpan = document.getElementById('data');
  var labels = JSON.parse(labelsSpan.textContent);
  var name = JSON.parse(nameSpan.textContent);
  var data = JSON.parse(dataSpan.textContent);
  updateChart(labels, name, data);
});

// chart.js
var ctx = document.getElementById('myChart').getContext('2d');
function updateChart(labels, name, data) {
  var config = {
    type: 'line',
    data: {
      labels: labels.map(function (label) {
        var date = new Date(label);
        return formatDate(date);
      }),
      datasets: [{
        label: name,
        data: data
      }]
    },
    options: {
      legend: {
        display: true
      }
    }
  };
  new Chart(ctx, config);
}
function formatDate(date) {
  var options = {
    year: '2-digit',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  };
  return date.toLocaleDateString('en-GB', options).replace(',', '');
}
function submitForm(currencyId) {
  document.getElementById('currencyIdInput').value = currencyId;
  document.getElementById('updateChartCurrency').submit();
}

// datatable.js
$(document).ready(function () {
  $('#currencyTable').DataTable({
    paging: false,
    searching: false,
    language: {
      info: "Select a cryptocurrency to display the chart"
    }
  });
});
/******/ })()
;