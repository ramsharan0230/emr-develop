<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <agewisedetails
      ref="age_wise_details_ref"
      height="430"
      type="bar"
      :options="chartOptions"
      :series="series"
    ></agewisedetails>
  </div>
</template>

<script>
import { AGE_WISE_DETAILS } from "../../routes";
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader.vue";

export default {
  name: "age-wise-details",
  components: {
    agewisedetails: VueApexCharts,
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      series: [
        {
          name: "Male",
          data: [],
        },
        {
          name: "Female",
          data: [],
        },
      ],
      chartOptions: {
        chart: {
          type: "bar",
        },
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              position: "top",
            },
          },
        },
        dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
            fontSize: "14px",
            colors: ["#fff"],
          },
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["#fff"],
        },
        tooltip: {
          shared: true,
          intersect: false,
        },
        xaxis: {
          categories: ["0-9", "10-19 ", "20-59", "above 59"],
          crosshairs: {
            show: false,
            width: 1,
          },
        },
      },
    };
  },
  mounted: function () {
    this.uChart();
  },
  methods: {
    uChart: function () {
      axios.post(AGE_WISE_DETAILS, null).then((response) => {
        this.series = [
          {
            name: "Male",
            data: response.data.male,
          },
          {
            name: "Female",
            data: response.data.female,
          },
        ];
        this.isLoading = false;
      });
    },
  },
};
</script>

<style scoped>
</style>
