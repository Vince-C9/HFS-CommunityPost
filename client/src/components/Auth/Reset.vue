<template>
  <div class="row login-form">
    <div class="column">
      <div class="col-md-12">
        <header>
          <img src="/images/logo.png" class="logo" />
        </header>
        <div class="text-center">
          <h4>Provide a new password</h4>
          <p>Please provide and confirm your new password.</p>
        </div>
        
        <form>
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
            <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" v-on:click="resetPassword" v-bind:disabled="loading">Reset Password</button>
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
  name: 'ResetPassword',
  data() {
    return {
      password: '',
      confirmPassword: '',
      loading: false,
      error: '',
      goodMessage: ''
    }
  },
  methods: {
    resetPassword: function(){
      this.error = '';
      this.goodMessage = '';
      this.loading = true;

       if( this.password!='' && this.confirmPassword!='' && this.password == this.confirmPassword){
          //Do Login Attempt
          axios.post('http://localhost:8000/api/auth/reset-password', {
            password: this.password,
            password_confirmation: this.confirmPassword,
            email:this.$route.params.email,
            token:this.$route.params.token
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
        this.loading = false;
        this.error = "Please provide and confirm your password.  Both values must match!";
       }
      
    },
    
  }
})
</script>
