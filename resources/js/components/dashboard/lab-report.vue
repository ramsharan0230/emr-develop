<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <labreports
      ref="lab_reports_ref"
      :options="chartOptions"
      height="430"
      type="bar"
      :series="series"
    ></labreports>
  </div>
</template>

<script>
import { LAB_REPORTS } from "../../routes";
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader.vue";

export default {
  name: "lab-reports",
  components: {
    labreports: VueApexCharts,
    loader: Loader,
  },
  data() {
    return {
      isLoading: true,
      series: [],
      chartOptions: {
        chart: {
          type: "bar",
          height: 800,
        },
        plotOptions: {
          bar: {
            horizontal: false,
            dataLabels: {
              position: "top",
            },
          },
        },
        dataLabels: {
          enabled: false,
          offsetX: 0,
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
      axios.get(LAB_REPORTS).then(async (response) => {
        await this.updateTheme(response);
        this.isLoading = false;
      });
    },
    updateTheme(response) {
      this.chartOptions = {
        xaxis: {
          crosshairs: {
            show: false,
            width: 1,
          },
          categories: response.data.categories,
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
      ];
    },
  },
};
</script>

<style scoped>
</style>
