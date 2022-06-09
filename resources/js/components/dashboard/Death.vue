<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <b>{{ deathCount.death }}</b> Total Deaths
  </div>
</template>
<style scoped>
.vue-loader {
  margin-top: 40px;
}
</style>
<script>
import { DEATH_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      deathCount: {
        death: 0,
      },
    };
  },
  mounted() {
    axios.get(DEATH_URL).then((response) => {
      // console.log(response.data.data)
      Object.assign(this.deathCount, {
        death: response.data.data.death,
      });
      this.isLoading = false;
    });
  },
};
</script>
