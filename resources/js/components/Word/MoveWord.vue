<template>
  <div class="container">
    <h4>Move Word <span class="alert-primary p-2">{{ word.word }}</span></h4>
    <div class="row">

      <div class="col-8 mx-auto mt-4">

        <div class="alert-danger p-2 mb-2" v-if="errors.errors">
          <div v-for="err in errors.errors">
            <span class="d-block">{{ err }}</span>
          </div>
        </div>

        <div class="alert-success p-2 mb-2" v-if="success.message">
          {{ success.message }}
        </div>

        <dates-taken :taken="this.taken"/>

        <form @submit.prevent="moveWord">

          <div class="form-group">
            <label for="longdate">Current word date</label>
            <div class="text-danger mb-2" v-if="this.errors.longdate">{{ this.errors.longdate}}</div>
            <div class="alert-warning p-1 font-weight-bold">{{ fields.longdate }}</div>
          </div>

          <div class="form-group">
            <label for="longdate">New word date</label>
            <div class="text-danger mb-2" v-if="this.errors.longdate">{{ this.errors.longdate}}</div>
            <flat-pickr @input="checkDate" v-model="fields.newDate" type="text" class="form-control bg-white" name="longdate" placeholder="YYYY-MM-DD" />
          </div>

          <button type="submit" class="btn btn-primary">Move word</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import flatPickr from 'vue-flatpickr-component'
  import 'flatpickr/dist/flatpickr.css';
  import axios from "axios";

  export default {
    name: "MoveWord",
    props: ['word', 'taken'],
    created() {
      console.log(this.word);
      console.log(this.taken);
      this.fields.word = this.word.word;
      this.fields.longdate = this.word.longdate;
      this.fields.newDate = this.word.longdate;
    },
    data () {
      return {
        fields: {
          word: null,
          longdate: null,
          newDate: null
        },
        errors: { newDate: null, errors: null },
        success: { message: null }
      }
    },
    components: {
      flatPickr
    },
    methods: {
      checkDate() {
        console.warn(this.fields.newDate);
        axios.post(`/exists/${this.fields.newDate}`).then( resp => {
          console.warn(resp);

          if (resp.data.success) {
            this.success.message = null;
            this.errors.errors = [resp.data.success];
          } else {
            this.errors.errors = null;
            this.success.message = resp.data.error;
          }

        })
      },
      moveWord() {
        axios.post(`/move/${this.word.id}`, { newDate: this.fields.newDate }).then( res => {

          /* Log success */
          this.success.message = res.data.success;
          console.warn('Word has been moved');
          console.warn(res.data);
          // console.warn(res.data.record);

        }).catch(err => {

          /* Log errors */

          console.warn(err.response);
          console.warn(err.response.data);
          this.errors.errors = [err.response, err.response.data.error];
        });
      }
    }
  }
</script>

<style scoped>

</style>