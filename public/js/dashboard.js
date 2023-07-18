/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/dashboard.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   highlightRow: () => (/* binding */ highlightRow)
/* harmony export */ });
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
  $('#dateRangeSelect').on('change', function () {
    var dateRange = $(this).val();
    sendDateRange(dateRange);
  });
  $('#currencyTable tbody').on('click', 'tr', function () {
    var currencyId = $(this).data('currencyid');
    sendCurrency(currencyId);
  });
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
function highlightRow(currencyId) {
  $('#currencyTable tbody tr').removeClass('table-primary'); // Удаляем класс highlight у всех строк таблицы
  $('#currencyTable tbody tr[data-currencyid="' + currencyId + '"]').addClass('table-primary'); // Добавляем класс highlight выбранной строке
}
/******/ })()
;