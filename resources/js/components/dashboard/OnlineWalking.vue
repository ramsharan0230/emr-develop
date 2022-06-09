<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <div class="detail-horz dh-2">
      <div class="icon-box-wrapper">
        <div class="icon-box">E</div>
      </div>

      <div class="total">
        <p class="title title1">Online Patients</p>
        <span> {{ onlineWalking.online }} </span>
      </div>
    </div>

    <div class="detail-horz dh-1">
      <div class="icon-box-wrapper">
        <div class="icon-box">W</div>
      </div>

      <div class="total">
        <p class="title title1">Walkin Patients</p>
        <span> {{ onlineWalking.walking }} </span>
      </div>
    </div>
  </div>
</template>

<script>
import { ONLINE_WALKING_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      onlineWalking: {
        online: 0,
        walking: 0,
      },
    };
  },
  mounted() {
    axios.get(ONLINE_WALKING_URL).then((response) => {
      // console.log(response.data.data)
      Object.assign(this.onlineWalking, {
        online: response.data.data.online,
        walking: response.data.data.walking,
      });
      this.isLoading = false;
    });
  },
};
</script>
