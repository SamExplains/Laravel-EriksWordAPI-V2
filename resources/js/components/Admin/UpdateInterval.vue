<template>
  <div class="col-8 mb-2">

    <h5>Set word update interval</h5>

    <div v-if="success">
      <div class="row mb-2">
        <div class="col-12">
          <div class="alert-success p-2">
            Current stored interval is {{ success }}
          </div>
        </div>
      </div>
    </div>

    <div v-if="errors">
      <div class="row mb-2">
        <div class="col-12">
          <div class="alert-danger p-2">
            {{ errors }}
          </div>
        </div>
      </div>
    </div>

    <div class="input-group mb-3">
      <input type="number" class="form-control" placeholder="i.e 7" v-model="interval.interval">
      <div class="input-group-append">
        <button class="btn btn-primary" type="button" @click.prevent="updateInterval">Update interval</button>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from "axios";

  export default {
    name: "UpdateInterval",
    beforeCreate() {

      axios.get('/interval').then( resp => {
        console.warn('Got interval information!');
        // console.warn(resp);
        this.success = resp.data.interval.interval;
        this.interval = resp.data.interval;
      }).catch(err => {
        // console.warn(err);
        // console.warn(err.response.data);
      })

    },
    data() {
      return {
        success: null,
        interval: {
          interval : null
        },
        errors: null
      }
    },
    methods: {
      updateInterval() {

        if(this.interval.interval) {
          console.warn(this.interval);

          axios.post(`/interval`, {id: this.interval.id, new_interval: this.interval.interval}).then( resp => {

            console.warn(resp);
            this.success = 'Interval was updated';
            this.errors = null;

          }).catch( err => {
            console.warn(err.response);
            console.warn(err.response.data);
          })


        } else {
          this.success = null;
          this.errors = 'Interval can not be left blank.';
          console.warn("Interval is blank");
        }

      }
    }
  }
</script>

<style scoped>

</style>