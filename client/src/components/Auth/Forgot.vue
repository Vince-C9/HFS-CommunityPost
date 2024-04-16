<template>
  <div class="row login-form">
    <div class="column">
      <div class="col-md-12">
        <header>
          <img src="/images/logo.png" class="logo" />
        </header>
        <div class="text-center">
          <h4>Please provide your account email.</h4>
          <p>We need this in order to verify who you are and provide you with a reset password link.</p>
        </div>
        
        <form>
          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-model="email"/>
            <label class="form-label" for="email">Email address</label>
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
            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" v-on:click="forgotPassword">Send my reset password link</button>
          </div>
          

          <!-- Register buttons -->
          <div class="text-center">
            <p>Back to <a href="/">Login</a></p>
            
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
  name: 'ForgotPassword',
  data() {
    return {
      
      email: '',
      loading: false,
      error: '',
      goodMessage: ''
    }
  },
  methods: {
    forgotPassword: function(){
      this.error = '';
      this.goodMessage = '';
      this.loading = true;

       if(this.email!=''){
          //Do Login Attempt
          axios.post('http://localhost:8000/api/auth/forgot-password', {
            email: this.email,
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
        alert("Please provide us with your email.");
       }
      
    },
    
  }
})
</script>
