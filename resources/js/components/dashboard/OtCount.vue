<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <div class="current-detail">
      <div>
        <div class="icon-box1">
          <i class="ri-service-line"></i>
        </div>
        <div>
          <b>{{ OtCount.total }}</b> Total
        </div>
      </div>
      <div class="">
        <div>
          <b>{{ OtCount.major }}</b> Major
        </div>

        <div>
          <b>{{ OtCount.minor }}</b> Minor
        </div>

        <div>
          <b>{{ OtCount.intermediate }}</b> Intermediate
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { OT_COUNT_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      OtCount: {
        major: 0,
        minor: 0,
        intermediate: 0,
        total: 0,
      },
    };
  },
  mounted() {
    axios.get(OT_COUNT_URL).then((response) => {
      // console.log(response.data.data)
      Object.assign(this.OtCount, {
        major: response.data.data.major,
        minor: response.data.data.minor,
        intermediate: response.data.data.intermediate,
        total: response.data.data.total,
      });
      this.isLoading = false;
    });
  },
};
</script>

<style scoped>
</style>
