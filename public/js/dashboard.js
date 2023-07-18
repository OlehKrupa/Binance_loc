/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/dashboard.js ***!
  \***********************************/
var myChart;
$(document).ready(function () {
  $('#currencyTable').DataTable({
    paging: false,
    searching: false,
    language: {
      info: "Select a cryptocurrency to display the chart"
    }
  });
  highlightRow("{{ $choosenID }}");

  //Костиль? сесія працює для айдішника, не праюцє для дати
  var selectedDateRange = localStorage.getItem('selectedDateRange');
  if (selectedDateRange) {
    $('#dateRangeSelect').val(selectedDateRange);
  }
  var labelsSpan = document.getElementById('labels');
  var nameSpan = document.getElementById('name');
  var dataSpan = document.getElementById('data');
  var ctx = document.getElementById('myChart').getContext('2d');
  var labels = JSON.parse(labelsSpan.textContent);
  var name = JSON.parse(nameSpan.textContent);
  var data = JSON.parse(dataSpan.textContent);
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
  myChart = new Chart(ctx, config);
});
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
function sendDateRange(dateRange) {
  localStorage.setItem('selectedDateRange', dateRange);
  var dataToSend = {
    newDateRange: dateRange
  };
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "/home/sendDateRange",
    type: "POST",
    data: dataToSend,
    success: function success(response) {
      $('#labels').text(JSON.stringify(response.serverVariable.labels));
      $('#name').text(JSON.stringify(response.serverVariable.name));
      $('#data').text(JSON.stringify(response.serverVariable.data));
      var ctx = $('myChart');
      myChart.data.labels = response.serverVariable.labels.map(function (label) {
        var date = new Date(label);
        return formatDate(date);
      });
      myChart.data.datasets[0].label = response.serverVariable.name;
      myChart.data.datasets[0].data = response.serverVariable.data;
      myChart.update();
    },
    error: function error(xhr, status, _error) {
      console.error("Error:", _error);
    }
  });
}
function sendCurrency(currencyId) {
  var dataToSend = {
    newCurrencyId: currencyId
  };
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "/home/sendCurrency",
    type: "POST",
    data: dataToSend,
    success: function success(response) {
      highlightRow(currencyId);
      $('#labels').text(JSON.stringify(response.serverVariable.labels));
      $('#name').text(JSON.stringify(response.serverVariable.name));
      $('#data').text(JSON.stringify(response.serverVariable.data));
      var ctx = $('myChart');
      myChart.data.labels = response.serverVariable.labels.map(function (label) {
        var date = new Date(label);
        return formatDate(date);
      });
      myChart.data.datasets[0].label = response.serverVariable.name;
      myChart.data.datasets[0].data = response.serverVariable.data;
      myChart.update();
    },
    error: function error(xhr, status, _error2) {
      console.error("Error:", _error2);
    }
  });
}
function submitForm(currencyId) {
  document.getElementById('currencyIdInput').value = currencyId;
  document.getElementById('updateChartCurrency').submit();
}
function highlightRow(currencyId) {
  $('#currencyTable tbody tr').removeClass('table-primary'); // Удаляем класс highlight у всех строк таблицы
  $('#currencyTable tbody tr[data-currencyid="' + currencyId + '"]').addClass('table-primary'); // Добавляем класс highlight выбранной строке
}
/******/ })()
;