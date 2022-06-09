<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <radiologyreports
      ref="radiology_report_ref"
      :options="chartOptions"
      height="430"
      type="bar"
      :series="series"
    ></radiologyreports>
  </div>
</template>

<script>
import { RADIOLOGY_REPORTS } from "../../routes";
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader.vue";

export default {
  name: "radiology-reports",
  components: {
    radiologyreports: VueApexCharts,

    loader: Loader,
  },
  data() {
    return {
      isLoading: true,
      series: [],
      chartOptions: {
        chart: {
          type: "bar",
          height: 430,
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
          offsetX: 1,
          style: {
            fontSize: "11px",
            colors: ["#fff"],
          },
        },
        stroke: {
          show: true,
          width: 1,
          colors: ["#fff"],
        },
        tooltip: {
          shared: true,
          intersect: false,
        },
      },
    };
  },
  mounted() {
    this.uChart();
  },
  methods: {
    async uChart() {
      this.isLoading = true;
      axios.get(RADIOLOGY_REPORTS).then(async (response) => {
        await this.updateTheme(response);
        this.isLoading = false;
      });
    },
    updateTheme(response) {
      this.chartOptions = {
        xaxis: {
          categories: response.data.categories,
          crosshairs: {
            show: false,
            width: 1,
          },
          //   title: {
          //     text: "Test Category",
          //   },
        },
      };
      this.series = [
        {
          name: "Reported",
          data: response.data.Reported,
        },
        {
          name: "Sampled",
          data: response.data.Sampled,
        },
        {
          name: "Verified",
          data: response.data.Verified,
        },
        {
          name: "Ordered",
          data: response.data.Ordered,
        },
        {
          name: "Waiting",
          data: response.data.Waiting,
        },
      ];
    },
  },
};
</script>

<style scoped>
</style>
