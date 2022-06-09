<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <div class="current-detail">
      <div>
        <div>
          <i class="fas fa-bed"></i>
        </div>
        <b>{{ currentDischarged.total }}</b> Total Beds
      </div>
      <div class="">
        <div>
          <b>{{ currentDischarged.current }}</b> Current
        </div>

        <div>
          <b>{{ currentDischarged.discharged }}</b> Discharge
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { CURRENT_INPATIENT_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      currentDischarged: {
        current: 0,
        discharged: 0,
        total: 0,
      },
      isLoading: true,
    };
  },
  mounted() {
    axios.get(CURRENT_INPATIENT_URL).then((response) => {
      // console.log(response.data.data)
      Object.assign(this.currentDischarged, {
        current: response.data.data.current,
        discharged: response.data.data.discharged,
        total: response.data.data.total,
      });
      this.isLoading = false;
    });
  },
};
</script>

