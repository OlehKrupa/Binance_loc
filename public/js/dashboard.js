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
  // Initialize DataTable for currencyTable
  $('#currencyTable').DataTable({
    paging: false,
    searching: false,
    language: {
      info: "Select a cryptocurrency to display the chart"
    }
  });

  // Store the selected date range in local storage
  var selectedDateRange = localStorage.getItem('selectedDateRange');
  if (selectedDateRange) {
    $('#dateRangeSelect').val(selectedDateRange);
  }

  // Get data from HTML spans
  var labelsSpan = document.getElementById('labels');
  var nameSpan = document.getElementById('name');
  var dataSpan = document.getElementById('data');
  var choosenIDSpan = document.getElementById('choosenID');
  var ctx = document.getElementById('myChart').getContext('2d');

  // Parse the chart data from the spans
  var labels = JSON.parse(labelsSpan.textContent);
  var name = JSON.parse(nameSpan.textContent);
  var data = JSON.parse(dataSpan.textContent);
  var choosenID = JSON.parse(choosenIDSpan.textContent);

  // Highlight the selected row on page load
  highlightRow(choosenID);

  // Configure the chart
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

  // Create the chart
  myChart = new Chart(ctx, config);

  // Event handler for date range select change
  $('#dateRangeSelect').on('change', function () {
    var dateRange = $(this).val();
    sendDateRange(dateRange);
  });

  // Event handler for clicking on a table row
  $('#currencyTable tbody').on('click', 'tr', function () {
    var currencyId = $(this).data('currencyid');
    sendCurrency(currencyId);
  });
});

// Format a date to a custom string format
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

// Send the selected date range to the server
function sendDateRange(dateRange) {
  localStorage.setItem('selectedDateRange', dateRange);
  var dataToSend = {
    newDateRange: dateRange
  };
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "/home",
    type: "POST",
    data: dataToSend,
    success: function success(response) {
      // Update the chart data
      $('#labels').text(JSON.stringify(response.serverVariable.labels));
      $('#name').text(JSON.stringify(response.serverVariable.name));
      $('#data').text(JSON.stringify(response.serverVariable.data));
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

// Send the selected currency to the server
function sendCurrency(currencyId) {
  var dataToSend = {
    newCurrencyId: currencyId
  };
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "/home",
    type: "POST",
    data: dataToSend,
    success: function success(response) {
      // Highlight the selected row and update the chart data
      highlightRow(currencyId);
      $('#labels').text(JSON.stringify(response.serverVariable.labels));
      $('#name').text(JSON.stringify(response.serverVariable.name));
      $('#data').text(JSON.stringify(response.serverVariable.data));
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

// Function to highlight the selected row in the DataTable
function highlightRow(currencyId) {
  $('#currencyTable tbody tr').removeClass('table-primary'); // Remove highlight class from all table rows
  $('#currencyTable tbody tr[data-currencyid="' + currencyId + '"]').addClass('table-primary'); // Add highlight class to the selected row
}
/******/ })()
;