<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <div class="current-detail">
      <div>
        <div class="icon-box1">
          <i class="fas fa-baby-carriage"></i>
        </div>
        <div>
          <b>{{ deliveryCount.total }}</b> Total
        </div>
      </div>
      <div class="">
        <div v-for="(value, key) in items" :key="key">
          <div v-if="value > 0">
            <b>{{ value }}</b
            >&nbsp; {{ key }}
          </div>
        </div>
      </div>
      <div></div>
    </div>
  </div>
</template>

<script>
import { DELIVERY_COUNT } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      deliveryCount: {
        total: 0,
      },
      items: [],
    };
  },
  mounted() {
    axios.get(DELIVERY_COUNT).then((response) => {
      Object.assign(this.deliveryCount, {
        total: response.data.total,
      });
      this.items = response.data.deliveries;
      this.isLoading = false;
    });
  },
};
</script>

<style scoped>
</style>
