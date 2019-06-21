<template>
  <div>

    <div v-if="success" class="alert-success p-2 mb-3">
      {{ success }}
    </div>

    <div class="input-group mb-3">
      <input type="suggestion" class="form-control" placeholder="example-word" v-model="scraped">
      <div class="input-group-append">
        <button class="btn btn-primary" type="button" @click.prevent="suggestWord">Suggest new word</button>
      </div>
    </div>
  </div>

</template>

<script>
  import axios from 'axios';

  export default {
    name: "SuggestWord",
    data() {
      return{
        scraped: null,
        success: null
      }
    },

    methods: {
      suggestWord() {
        axios.get('/suggest-word').then( response => {
          console.warn(response);
          this.scraped = response.data.suggested;
          this.success = `Word ${response.data.suggested} was generated`;
        }).catch(error => {
          console.warn('No suggestion');
        })
      }
    }
  }
</script>

<style scoped>

</style>
<!--


-->