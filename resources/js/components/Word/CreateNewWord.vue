<template>

  <div class="container">
    <h4>Add New Word</h4>
    <div class="row">

      <div class="col-8 mx-auto mt-4">
        <!--<form method="POST" action="{{ route('word.store') }}">-->
        <form @submit.prevent="onNewWord">
          <!--@csrf-->
          <div class="form-group">
            <label for="longdate">Date</label>
            <div class="text-danger mb-2" v-if="this.date_feedback">{{ this.date_feedback}}</div>
            <flat-pickr v-model="fields.date" type="text" class="form-control bg-white" name="longdate" id="longdate" placeholder="YYYY-MM-DD"></flat-pickr>
          </div>

          <div class="form-group">
            <label for="word">Word</label>
            <div class="text-danger mb-2" v-if="this.word_feedback">{{ this.word_feedback}}</div>
            <input type="text" class="form-control" v-model="fields.word" id="word" aria-describedby="Word">
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

  export default {
    name: "CreateNewWord",
    data () {
      return {
        fields: {},
        word_feedback: null,
        date_feedback: null
      }
    },
    components: {
      flatPickr
    },
    methods: {
      onNewWord() {

        if (!this.fields.date)
          this.date_feedback = 'Please enter a date';
        else
          this.date_feedback = null;

        if (!this.fields.word)
          this.word_feedback = 'Please enter a word';
        else
          this.word_feedback = null;

        if (this.fields.date && this.fields.word) {

          axios.post('/word', this.fields).then(response => {
            console.log(response)
          }).catch( err => {
            console.warn(err);
          })


        }


      }
    }
  }
</script>

<!--     $('#longdate').flatpickr({dateFormat: "Y-m-d" }); -->

<style scoped>

</style>