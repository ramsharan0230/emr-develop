<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <div class="current-detail">
      <div>
        <div class="icon-box1">
          <i class="ri-medicine-bottle-fill"></i>
        </div>
        <div>
          <b>{{ pharmacyCount.total }}</b> Total
        </div>
      </div>

      <div class="">
        <div>
          <b>{{ pharmacyCount.ip }}</b> IP
        </div>

        <div>
          <b>{{ pharmacyCount.op }}</b> OP
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { PHARMACY_COUNT_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      pharmacyCount: {
        ip: 0,
        op: 0,
        total: 0,
      },
    };
  },
  mounted() {
    axios.get(PHARMACY_COUNT_URL).then((response) => {
      // console.log(response.data.data)
      Object.assign(this.pharmacyCount, {
        ip: response.data.data.ip,
        op: response.data.data.op,
        total: response.data.data.total,
      });
      this.isLoading = false;
    });
  },
};
</script>

