<template>
  <div class="vue-template">
    <h4 class="mb-2">Fiscal Year: {{ fiscalYear.fldname }}</h4>
  </div>
</template>

<script>
import { FISCAL_YEAR_URL } from "../../routes";
import Loader from "./loader.vue";

export default {
  components: {
    loader: Loader,
  },
  data: function () {
    return {
      isLoading: true,
      fiscalYear: {
        fldname: "",
      },
    };
  },
  mounted() {
    axios.get(FISCAL_YEAR_URL).then((response) => {
      // console.log(response.data.data.fiscal_year.fldname);
      Object.assign(this.fiscalYear, {
        fldname: response.data.data.fiscal_year,
      });
      // console.log(response.data)
      this.isLoading = false;
    });
  },
};
</script>
