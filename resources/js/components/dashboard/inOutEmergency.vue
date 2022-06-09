<template>
  <div class="vue-template">
    <div class="card-data-box top-cards">
      <div class="total">
        <p class="title">Total</p>
        <span>{{ inOutEmergency.total }} </span>
      </div>
      <div class="vertical-bar">
        <div class="bar bar-1" style="height: 33%"></div>
        <div class="bar bar-2" style="height: 33%"></div>
        <div class="bar bar-3" style="height: 33%"></div>
      </div>
      <div class="detail">
        <div class="total">
          <p class="title title1">In Patients</p>
          <span> {{ inOutEmergency.inPatient }} </span>
        </div>

        <div class="total">
          <p class="title title2">Out Patients</p>
          <span> {{ inOutEmergency.outPatient }} </span>
        </div>

        <div class="total">
          <p class="title title3">Emergency</p>
          <span> {{ inOutEmergency.emergency }} </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { IN_OUT_EMERGENCY_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      inOutEmergency: {
        total: 0,
        inPatient: 0,
        outPatient: 0,
        emergency: 0,
      },
      isLoading: true,
    };
  },
  mounted() {
    axios.get(IN_OUT_EMERGENCY_URL).then((response) => {
      // console.log(response.data.data.total)
      Object.assign(this.inOutEmergency, {
        total: response.data.data.total,
        inPatient: response.data.data.inPatient,
        outPatient: response.data.data.outPatient,
        emergency: response.data.data.emergency,
      });
      this.isLoading = false;
    });
  },
};
</script>

<style scoped>
</style>
