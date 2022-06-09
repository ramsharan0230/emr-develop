<template>
  <div class="vue-template">
    <p class="card-title">Revenue Details</p>
    <div class="row">
      <div class="col-md-4">
        <div
          class="btn-group btn-group-xs"
          role="group"
          aria-label="Basic outlined example"
        >
          <button
            type="button"
            :class="['btn btn-outline-primary', { active: dateType == 'Week' }]"
            @click="dateType = 'Week'"
          >
            Weekly
          </button>
          <button
            type="button"
            :class="[
              'btn btn-outline-primary',
              { active: dateType == 'Month' },
            ]"
            @click="dateType = 'Month'"
          >
            Monthly
          </button>
        </div>
        <br />
      </div>

      <div class="col-md-4 text-center">
        <select
          v-model="paymentType"
          class="form-select form-select-sm form-control"
        >
          <option value="">Payment Mode</option>
          <option
            v-for="billing in billings"
            :key="billing.fldsetname"
            :value="billing.fldsetname"
          >
            {{ billing.fldsetname }}
          </option>
        </select>
      </div>

      <div class="col-md-4 text-right">
        <select
          v-model="department"
          class="form-select form-select-sm form-control"
        >
          <option value="">Departments</option>
          <option v-for="dep in departments" :key="dep.id" :value="dep.fldcomp">
            {{ dep.name }}
          </option>
        </select>
      </div>
    </div>
    <loader v-if="isLoading" class="vue-loader"></loader>
    <revenuedetails
      ref="revenue_details_ref"
      :options="chartOptions"
      height="430"
      type="line"
      :series="series"
    ></revenuedetails>
  </div>
</template>

<script>
import { REVENUE_DETAILS } from "../../routes";
import VueApexCharts from "vue-apexcharts";
import Loader from "./loader.vue";

export default {
  name: "revenue-details",
  components: {
    revenuedetails: VueApexCharts,
    loader: Loader,
  },
  props: {
    billings: {
      type: Array,
      default: [],
    },
    departments: {
      type: Array,
      default: [],
    },
  },
  data: function () {
    return {
      isLoading: true,
      dateType: "Month",
      paymentType: "",
      department: "",
      series: [
        {
          name: "Inpatient",
          data: [],
        },
        {
          name: "Outpatient",
          data: [],
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
            text: this.dateType,
          },
        },
        yaxis: {
          title: {
            text: "Revenue",
          },
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
  watch: {
    dateType() {
      this.uChart();
    },
    paymentType() {
      this.uChart();
    },
    department() {
      this.uChart();
    },
  },
  mounted: function () {
    this.uChart();
  },
  methods: {
    async uChart() {
      this.isLoading = true;
      axios
        .post(REVENUE_DETAILS, {
          dateType: this.dateType,
          paymentType: this.paymentType,
          department: this.department,
        })
        .then(async (response) => {
          await this.updateTheme(response);
          this.isLoading = false;
        });
    },
    updateTheme(response) {
      this.chartOptions = {
        xaxis: {
          categories: response.data.labels,
          crosshairs: {
            show: false,
            width: 1,
          },
          title: {
            text: this.dateType,
          },
        },
      };
      this.series = [
        {
          name: "Inpatient",
          data: response.data.RevenuePatientIn,
        },
        {
          name: "Outpatient",
          data: response.data.RevenuePatientOut,
        },
      ];
    },
  },
};
</script>

<style scoped>
</style>
