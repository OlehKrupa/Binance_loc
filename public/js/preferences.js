/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************!*\
  !*** ./resources/js/preferences.js ***!
  \*************************************/
$(document).ready(function () {
  $('#currencyTable').on('click', '.checkmark', function (e) {
    e.stopPropagation();
    var checkbox = $(this).siblings('input[type="checkbox"]');
    checkbox.prop('checked', !checkbox.prop('checked'));
  });
  $('#currencyTable').on('click', 'tr', function (e) {
    var checkbox = $(this).find('input[type="checkbox"]');
    checkbox.prop('checked', !checkbox.prop('checked'));
  });
  $('#currencyTable').DataTable({
    scrollY: '600px',
    scrollCollapse: true,
    paging: false,
    dom: 'lfrt'
  });
});
/******/ })()
;