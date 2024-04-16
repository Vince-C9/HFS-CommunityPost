<template>
  <div class="row login-form">
    <div class="column">
      <div class="col-md-12">
        <header>
          <img src="/images/logo.png" class="logo" />
        </header>
        <form>
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="text" id="name" class="form-control" v-model="name" v-bind:disabled="loading"/>
            <label class="form-label" for="name">Name</label>
          </div>
          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-model="email" v-bind:disabled="loading"/>
            <label class="form-label" for="email">Email address</label>
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" id="password" class="form-control" v-model="password" v-bind:disabled="loading"/>
            <label class="form-label" for="password">Password</label>
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" id="confirm-password" class="form-control" v-model="confirmPassword" v-bind:disabled="loading"/>
            <label class="form-label" for="confirm-password">Confirm Password</label>
          </div>

          <div class="form-outline mb-4 error" v-show="error">
            {{ error }}
          </div>

          <div class="form-outline mb-4 success" v-show="goodMessage">
            {{ goodMessage }}
          </div>

          <div class="loading" v-show="loading">
            <img src="/images/loader.gif" />
          </div>

          <!-- Submit button -->
          <div class="text-center">
            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" v-on:click="register" v-bind:disabled="loading">Register</button>
          </div>
          

          <!-- Register buttons -->
          <div class="text-center">
            <p>Already a member? <a href="/">Login</a></p>
            
          </div>
        </form>
      </div>
    </div>
  </div>
  
</template>

<style scoped>

</style>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import axios from 'axios'


export default defineComponent({
  name: 'Register',
  data() {
    return {
      name:'',
      email: '',
      password: '',
      confirmPassword: '',
      loading: false,
      error: '',
      goodMessage: ''
    }
  },
  methods: {
    register: function(){
      this.error = '';
      this.goodMessage = '';
      this.loading = true;

       if(this.email!='' && this.password!='' && this.confirmPassword!='' && this.name!=''){
          //Do Login Attempt
          axios.post('http://localhost:8000/api/auth/register', {
            name: this.name,
            email: this.email,
            password: this.password,
            confirmPassword: this.confirmPassword
          })
          .then(res => {
            this.loading = false;
            this.goodMessage = res.data.message;

            let interval = setInterval(
                function () {
                    this.$router.push({path: "/"});
                }.bind(this),
                3000
            );
          })
          .catch((err) => {
            //Time saving - should display errors in the html
            this.error = err.response.data.message;
            
            this.loading = false;
          });
       }else{
        //To save time I've jut alerted where failures occur.  Wouldn't suggest this in a production product but 
        //need to save some time.
        this.loading = false;
        alert("Please fill in the whole form");
       }
      
    },
    
  }
})
</script>
