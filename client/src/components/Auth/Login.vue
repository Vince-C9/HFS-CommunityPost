<template>
  <div class="row login-form">
    <div class="column">
      <div class="col-md-12">
        <header>
          <img src="/images/logo.png" class="logo" />
        </header>
        <form>
          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="email" v-model="email" class="form-control" v-bind:disabled="loading"/>
            <label class="form-label" for="email">Email address</label>
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" v-model="password" id="password" class="form-control" v-bind:disabled="loading"/>
            <label class="form-label" for="password">Password</label>
          </div>
          
          <div class="form-outline mb-4 error" v-show="loginError">
            {{ loginError }}
          </div>

          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">
            </div>

            <div class="col">
              <!-- Simple link -->
              <a href="/forgot-password">Forgot password?</a>
            </div>
          </div>
          <div class="loading" v-show="loading">
            <img src="/images/loader.gif" />
          </div>

          <!-- Submit button -->
          <div class="text-center">
            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" v-on:click="login" v-bind:disabled="loading">Sign in</button>
          </div>
          

          <!-- Register buttons -->
          <div class="text-center">
            <p>Not a member? <a href="/register">Register</a></p>
            
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
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      loading: false,
      accessToken: '',
      loginError: ''
    }
  },
  methods: {
    login: function(){
       this.loading = true;

       if(this.email!='' && this.password!=''){
        console.log("Got Here");
          //Do Login Attempt
          axios.post('http://localhost:8000/api/auth/login', {
            email: this.email,
            password: this.password
          })
          .then(res => {
            this.loading = false;
            this.accessToken = res.data.access_token;
          })
          .catch((err) => {
            //Time saving - should display errors in the html
            this.loginError = err.response.data.message;
            
            this.loading = false;
          });
       }else{
        //To save time I've jut alerted where failures occur.  Wouldn't suggest this in a production product but 
        //need to save some time.
        this.loading = false;
        alert("Please provide an email and password");
       }
      
    },
    
  }
})

</script>
