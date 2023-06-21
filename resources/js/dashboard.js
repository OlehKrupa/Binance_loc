// main.js
import { updateChart } from './chart';
import './datatable';

$(document).ready(function() {
    const labelsSpan = document.getElementById('labels');
    const nameSpan = document.getElementById('name');
    const dataSpan = document.getElementById('data');

    const labels = JSON.parse(labelsSpan.textContent);
    const name = JSON.parse(nameSpan.textContent);
    const data = JSON.parse(dataSpan.textContent);

    updateChart(labels, name, data);
});