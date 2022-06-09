<template>
  <div class="vue-template">
    <loader v-if="isLoading" class="vue-loader"></loader>
    <div class="detail-horz dh-1">
      <div class="icon-box-wrapper">
        <div class="icon-box">NP</div>
      </div>

      <div class="total">
        <p class="title title1">New Patients</p>
        <span> {{ newOldFollowUp.newpatient }} </span>
      </div>
    </div>

    <div class="detail-horz dh-2">
      <div class="icon-box-wrapper">
        <div class="icon-box">OP</div>
      </div>

      <div class="total">
        <p class="title title1">Old Patients</p>
        <span> {{ newOldFollowUp.oldpatient }} </span>
      </div>
    </div>

    <div class="detail-horz dh-3">
      <div class="icon-box-wrapper">
        <div class="icon-box">FP</div>
      </div>

      <div class="total">
        <p class="title title1">Followup Patients</p>
        <span> {{ newOldFollowUp.followup }} </span>
      </div>
    </div>
  </div>
</template>

<script>
import { NEW_OLD_FOLLOW_UP_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      newOldFollowUp: {
        newpatient: 0,
        oldpatient: 0,
        followup: 0,
      },
    };
  },
  mounted() {
    axios.get(NEW_OLD_FOLLOW_UP_URL).then((response) => {
      // console.log(response.data.data)
      Object.assign(this.newOldFollowUp, {
        newpatient: response.data.data.newpatient,
        oldpatient: response.data.data.oldpatient,
        followup: response.data.data.followup,
      });
      this.isLoading = false;
    });
  },
};
</script>

<style scoped>
</style>
