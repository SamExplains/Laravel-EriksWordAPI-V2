<template>

  <div class="container">
    <h4>Add New Word</h4>
    <div class="row">

      <div class="col-8 mx-auto mt-4">

        <div class="alert-danger p-2 mb-2" v-if="errors.errors">
          <div v-for="err in errors.errors">
            <span class="d-block">{{ err[0].replace(err[0], 'This date has already been taken') }}</span>
          </div>
        </div>

        <div class="alert-success p-2 mb-2" v-if="success.message">
          {{ success.message }}
        </div>

        <suggest-word />

        <dates-taken :taken="this.taken"/>


        <!--<form method="POST" action="{{ route('word.store') }}">-->
        <form @submit.prevent="onNewWord">
          <!--@csrf-->
          <div class="form-group">
            <label for="longdate">Date</label>
            <div class="text-danger mb-2" v-if="this.errors.longdate">{{ this.errors.longdate}}</div>
            <flat-pickr v-model="fields.longdate" type="text" class="form-control bg-white" name="longdate" placeholder="YYYY-MM-DD"></flat-pickr>
          </div>

          <div class="form-group">
            <label for="word">Word</label>
            <div class="text-danger mb-2" v-if="this.errors.word">{{ this.errors.word}}</div>
            <input type="text" class="form-control" v-model="fields.word" name="word" placeholder="Enter a word here" aria-describedby="Word">
          </div>

          <button type="submit" class="btn btn-primary">Add new word</button>
        </form>
      </div>
    </div>
  </div>

</template>

<script>
  import flatPickr from 'vue-flatpickr-component'
  import 'flatpickr/dist/flatpickr.css';
  import axios from "axios";
  import DatesTaken from "./DatesTaken";
  import SuggestWord from "./SuggestWord";

  export default {
    name: "CreateNewWord",
    props: ['taken'],
    data () {
      return {
        fields: {
          word: null,
          longdate: null,
        },
        errors: { longdate: null, word: null, update: null, errors: null },
        success: { message: null }
      }
    },
    components: {
      SuggestWord,
      DatesTaken,
      flatPickr
    },
    methods: {
      onNewWord() {

        (!this.fields.longdate) ? this.errors.longdate = 'Please enter a date' : this.errors.longdate = null;

        (!this.fields.word) ? this.errors.word = 'Please enter a word' : this.errors.word = null;

        if (this.fields.longdate && this.fields.word) {

          axios.post('/word', {longdate: this.fields.longdate, word: this.fields.word }).then(response => {
            // console.log(response.data.D);
            console.log(response);

            /* Update success message */
            this.success.message = response.data.success;

            /* Reset binded fields */
            this.fields.longdate = this.fields.word = this.fields.update = this.errors.errors = null;

            setTimeout( () => {
              this.success.message = null
            }, 1500)

          }).catch( err => {

            /* Update errors object to present erros */
            this.errors.errors = err.response.data.errors;
            console.warn(err.response.data);

          })

        }

      }
    }
  }
</script>

<!--     $('#longdate').flatpickr({dateFormat: "Y-m-d" }); -->

<style scoped>

</style>