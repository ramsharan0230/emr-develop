<template>
  <div class="vue-template">
    <radiodetails
      type="pie"
      :options="chartOptions"
      :series="series"
    ></radiodetails>
  </div>
</template>

<script>
import { RADIO_DETAILS } from "../../routes";
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader.vue";

export default {
  name: "radio-details",
  components: {
    radiodetails: VueApexCharts,
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      series: [],
      chartOptions: {
        chart: {
          width: 380,
          type: "pie",
        },
        legend: {
          offsetX: -40,
          offsetY: 0,
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
    };
  },
  mounted: function () {
    this.uChart();
  },
  methods: {
    async uChart() {
      axios.get(RADIO_DETAILS).then(async (response) => {
        await this.updateTheme(response);
        this.isLoading = false;
      });
    },
    updateTheme(response) {
      this.chartOptions = {
        labels: ["Waiting", "Sampled", "Reported", "Verified", "Ordered"],
      };
      this.series = response.data;
    },
  },
};
</script>

<style scoped>
</style>
