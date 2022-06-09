<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <labdetails
      ref="lab_details_ref"
      type="donut"
      :options="chartOptions"
      :series="series"
    ></labdetails>
  </div>
</template>

<script>
import { LAB_DETAILS } from "../../routes";
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader";

export default {
  name: "lab-details",
  components: {
    labdetails: VueApexCharts,
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      series: [],
      chartOptions: {
        chart: {
          type: "donut",
        },
        legend: {
          offsetX: -40,
          offsetY: 0,
        },
        responsive: [
          {
            options: {
              chart: {
                width: 320,
              },
            },
          },
        ],
      },
    };
  },
  mounted() {
    this.uChart();
  },
  methods: {
    async uChart() {
      axios.get(LAB_DETAILS).then(async (response) => {
        await this.updateTheme(response);
        this.isLoading = false;
      });
    },
    updateTheme(response) {
      this.chartOptions = {
        labels: ["Ordered", "Sampled", "Reported", "Verified"],
      };
      this.series = response.data;
    },
  },
};
</script>
