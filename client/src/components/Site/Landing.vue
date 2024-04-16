<template>
     <div class="container">
      <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
          <div class="col-4 pt-1">
            <a class="text-muted" href="#"><img src="/images/logo.png"></a>
          </div>
          <div class="col-4 text-center">
          </div>
          <div class="col-4 d-flex justify-content-end align-items-center">
            <a class="btn btn-sm btn-outline-secondary" href="#">Your Profile</a>
          </div>
        </div>
      </header>

      <div class="jumbotron p-3 p-md-5 text-white rounded bg-dark feature">
        <div class="col-md-6 px-0"> 
          <h1 class="display-4 font-italic">Welcome to tech talks!</h1>
          <p class="lead my-3">Your hub for all things tech and where the technical industry is going.  Discuss, upskill and teach others what you know!</p>
        </div>
      </div>
      <div class="col-md-12 loader" v-show="loading">
        <img src="/images/loader.gif" />
      </div>

      <div class="col-md-12" v-show="articles.length === 0 && !loading">
        <h4>Uh oh!  No articles yet!</h4>  
        <p>We don't seem to have any articles in our database yet. Why not make the first?</p>
      </div>

      <div class="row mb-2" v-show="articles.length > 0">
        <div class="col-md-12" v-for="article in articles">
            <div class="card flex-md-row mb-4 box-shadow h-md-250">
                <div class="card-body d-flex flex-column align-items-start">
                    <strong class="d-inline-block mb-2 text-primary">Author</strong>
                    <h3 class="mb-0">
                        {{article.title}}
                    </h3>
                    <div class="mb-1 text-muted">{{getFormattedDate(article.created_at) }}</div>
                    <div class="content">
                        <p class="card-text mb-auto">{{article.content}}</p>
                        <div class="comments">
                            <h6>COMMENTS:</h6>
                            <div v-show="!article.comment || article.comment.length === 0">
                                No Comments.
                            </div>
                            
                            <div class="comment-container" v-for="comment in article.comment">
                                <p>{{comment.comment }} - Author</p>
                                <p><small>123 replies - See More</small></p>
                                <hr>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
      </div>
    </div>

    

    <footer class="blog-footer">
      <p>Copyright Tech Talks 2024</p>
      <p>
        <a href="#">Back to top</a>
      </p>
    </footer>

    
    
  </template>
  
  <style scoped>
    .feature{
        margin-bottom: 25px;
    }
    .loader{
        text-align: center;
    }
    .loader img{
        width:100px;
    }
    .comment-container{
        width:100%;
    }

    .comments{
        margin-top: 25px;
        padding-top: 25px;
    }
    .comments h6{
        color:#999
    }
  </style>
  
  <script lang="ts">
  import { defineComponent, ref } from 'vue'
  import moment from 'moment';
  import axios from 'axios'
  
  
  export default defineComponent({
    name: 'Login',
    data() {
      return {
        loading: false,
        accessToken: null,
        error: '',
        articles: []
      }
    },
    created: function(){
        this.accessToken = sessionStorage.getItem('token');
        this.getArticles()
    },
    methods: {
      getArticles(){
        this.loading = true;
        let config = {
        headers: {
          Authorization: `Bearer ${this.accessToken}`,
        }
      }

      axios.get('http://localhost:8000/api/article', config)
      .then(res => {
        this.loading = false;
        this.articles = res.data.articles
        console.log(this.articles);
      })
      .catch((err) => {
        //I know this is bad, but I'd like to get this over the line for you by tomorrow! :) 
        alert(err);
        this.loading = false;
      });

      },
      getFormattedDate(date: string) {
            return moment(date).format("Do MMM YYYY")
        }
      
    }
  })
  
  </script>
  