<template>

  <div class="dropdown show mb-2">
    <a style="border-radius: 0" class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Dates taken
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="height: 20rem; overflow: auto !important;">
      <a class="dropdown-item" v-for="(una, index) in unavailable" :key="index">
        {{ una }} – {{ index }}
      </a>
    </div>
  </div>
  
</template>

<script>
  export default {
    name: "DatesTaken",
    props: ['taken'],
    created() {
      console.warn(this.taken);
      this.unavailable = this.taken;
    },
    data () {
      return {
        unavailable: null
      }
    }
  }
</script>

<style scoped>

</style>