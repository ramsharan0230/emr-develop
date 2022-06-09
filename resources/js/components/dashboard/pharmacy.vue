<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <pharmacydetails
      ref="pharmacy_details_ref"
      :options="chartOptions"
      height="430"
      type="line"
      :series="series"
    ></pharmacydetails>
  </div>
</template>

<script>
// import {PHARMACY_DETAILS} from '../../routes';
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader.vue";

export default {
  name: "pharmacy",
  components: {
    pharmacydetails: VueApexCharts,
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      series: [
        {
          name: "Inpatient",
          data: [100],
        },
        {
          name: "Outpatient",
          data: [200],
        },
      ],
      chartOptions: {
        chart: {
          height: 350,
          type: "line",
          dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
          },
          toolbar: {
            show: false,
          },
        },
        colors: ["#77B6EA", "#545454"],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: "smooth",
        },
        title: {
          text: ".",
          align: "left",
        },
        grid: {
          borderColor: "#e7e7e7",
          row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
          },
        },
        markers: {
          size: 1,
        },
        xaxis: {
          categories: [],
          crosshairs: {
            show: false,
            width: 1,
          },
          title: {
            text: "Month",
          },
        },
        yaxis: {
          title: {
            text: "Revenue",
          },
          min: 100,
          max: 5000000,
        },
        legend: {
          position: "top",
          horizontalAlign: "right",
          floating: true,
          offsetY: -25,
          offsetX: -5,
        },
      },
    };
  },
  /*mounted: function () {
        this.uChart()
    },
    methods: {
        uChart: function () {
            axios
                .post(PHARMACY_DETAILS, null)
                .then(response => {
                    this.series = [{
                        name: "Inpatient",
                        data: response.data.RevenuePatientIn.TotalSales
                    }, {
                        name: "Outpatient",
                        data: response.data.RevenuePatientOut.TotalSales
                    }];
                    this.chartOptions.xaxis = {
                        categories:response.data.months
                    };
                    this.isLoading = false;
                });

        }
    }*/
};
</script>

<style scoped>
</style>
