<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">{{ __('Your Currencies') }}</div>
            <div class="card-body">
              <div>
                <canvas ref="myChart"></canvas>
              </div>
              <label for="dateRangeSelect">Select Date Range:</label>
              <select id="dateRangeSelect" name="dateRange" v-model="startDate">
                <option value="6">6 hours</option>
                <option value="12">12 hours</option>
                <option value="24">1 day</option>
                <option value="48">2 days</option>
                <option value="168">7 days</option>
                <option value="720">30 days</option>
              </select>
  
              <div v-if="status" class="alert alert-success" role="alert">{{ status }}</div>
              <div v-if="errors.length > 0" class="alert alert-danger" role="alert">
                <div v-for="error in errors" :key="error">{{ error }}</div>
              </div>
  
              <div id="phpChartVariables" style="display: none;">
                <a id="labels" v-html="labels"></a>
                <a id="name" v-html="name"></a>
                <a id="data" v-html="data"></a>
                <a id="choosenID" v-html="choosenID"></a>
              </div>
  
              <form id="updateChartCurrency" v-if="lastCurrencies.length > 0" @submit.prevent="submitForm">
                <table id="currencyTable" class="table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Buy</th>
                      <th>Sell</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="currency in lastCurrencies" :key="currency.id" :data-currencyid="currency.id">
                      <td>{{ currency.name }}</td>
                      <td>${{ currency.buy.toFixed(2) }}</td>
                      <td>${{ currency.sell.toFixed(2) }}</td>
                    </tr>
                  </tbody>
                </table>
                <input type="hidden" name="currencyId" id="currencyIdInput">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        startDate: 6,
        status: null,
        errors: [],
        lastCurrencies: [],
        labels: '',
        name: '',
        data: '',
        choosenID: '',
      };
    },
    mounted() {
      this.initChartAndDataTable();
    },
    methods: {
      initChartAndDataTable() {
        // Add the Chart.js and DataTable initialization code here
        // (Please include the necessary script tags in your HTML)
        // You can keep the existing script code here.
  
        // Example:
        // const ctx = this.$refs.myChart.getContext('2d');
        // const myChart = new Chart(ctx, {
        //   // Chart configuration goes here
        // });
  
        // $(this.$refs.currencyTable).DataTable({
        //   // DataTable configuration goes here
        // });
      },
      submitForm() {
        // Handle the form submission if needed
      },
      // Add other functions as needed
    },
  };
  </script>
  
  <style>
  /* Add the necessary CSS styles here */
  </style>
  