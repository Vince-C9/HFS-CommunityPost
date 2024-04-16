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
      <div class="filters">
        <p>Order By: 
          <span class="filter" v-on:click="getArticles('asc')">Newest</span> | 
          <span class="filter" v-on:click="getArticles('desc')">Oldest</span> | 
          <span class="filter" v-on:click="getArticles('votes-desc')">Most Popular</span> | 
          <span class="filter" v-on:click="getArticles('votes-asc')">Least Popular</span></p>
      </div>
      <div class="col-md-12 loader" v-show="loading">
        <img src="/images/loader.gif" />
      </div>

      <div class="col-md-12" v-show="articles.length === 0 && !loading">
        <h4>Uh oh!  No articles yet!</h4>  
        <p>We don't seem to have any articles in our database yet. Why not make the first?</p>
      </div>

      <div class="row mb-2" v-show="articles.length > 0 && !loading">
        <p v-show="!loading">Ordered by: <span class='filter'>{{ filterLabel }}</span></p>
        <div class="col-md-12" v-for="article in articles">
            <div class="card flex-md-row mb-4 box-shadow h-md-250">
                <div class="card-body d-flex flex-column align-items-start">
                    <strong class="d-inline-block mb-2 text-primary">Author: {{ article.user.name }}</strong>
                    <h3 class="mb-0">
                        {{article.title}}
                    </h3>
                    <div class="mb-1 text-muted">{{getFormattedDate(article.created_at) }}</div>
                    <div class="content">
                        <p class="card-text mb-auto">{{article.content}}</p>
                        
                        <Vote :articleId="article.id" :userVote="0" :votes="article.vote" />
                        <div class="comments">
                            <h6>COMMENTS: 
                              <span v-show="article.comment.length >0">{{ article.comment.length }}</span>
                              <span class="chevron text-align-right" v-on:click="showComments(article)" v-show="article.comment.length>0">{{article.showComments ? 'Hide' : 'Show'}} Comments</span>
                            </h6>
                            
                            <div v-show="!article.comment || article.comment.length === 0">
                                No Comments.
                            </div>
                            <div class="comment-container" v-show="article.showComments" v-for="comment in article.comment" >
                                <p class="comment-main"><strong>{{comment.user.name}}</strong><br>"{{comment.comment }}"<br><small>On {{getFormattedDate(comment.created_at) }}</small></p>

                                <p class="indent border" v-for="reply in comment.replies">
                                  <strong>{{ reply.user.name }}</strong> 
                                  <br> Replies: {{ reply.comment }}  
                                  <br><small>On {{getFormattedDate(comment.created_at) }}</small>
                                    <p class="indent reply-margins" v-for="secondLevel in reply.replies">
                                      <strong>{{ secondLevel.user.name }}</strong> 
                                      <br> Replies: {{ secondLevel.comment }}  
                                      <br><small>On {{getFormattedDate(comment.created_at) }}</small>
                                    </p>
                                </p>

                                <!-- Really I should use a recursive template here, but I am running out of time and it's gone midnight!-->
                                
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
    .filters .filter{
      cursor: pointer;
    }
    .filter{
      text-decoration: underline;
      font-weight: bold;
    }
    .loader img{
        width:100px;
    }
    .content
    {
      width:100%;
    }
    .comment-container{
        width:100%;
    }
    .comment-container .indent{
      padding-left: 5%;
    }
    .comment-container .border{
      padding: 15px 15px 15px 5%;
      border: 1px solid #333 !important;
      border-top:0 !important;
      margin-bottom:0;
    }
    .reply-margins{
      margin-top:15px;
      margin-bottom:0;
    }
    .chevron{
      font-weight: bold;
      cursor: pointer;
    }
    .comments{
        margin-top: 25px;
        padding-top: 25px;
    }
    .comments h6{
        color:#999;
        width:100%;
    }
    .chevron{
      display: inline-block;
      float: right;
    }
    .hide{
      display: none;
    }
    .comment-container .comment-main{
      background:#eee;
      padding:15px;
      border-radius: 3px 3px 0 0;
      border:1px solid #333;
      margin-bottom:0;
    }
  </style>
  
  <script lang="ts">
  import { defineComponent, ref } from 'vue'
  import Vote from '../Shared/Vote.vue';
  import moment from 'moment';
  import axios from 'axios'
  
  
  export default defineComponent({
    name: 'Login',
    components: {
      Vote
    },
    data() {
      return {
        loading: false,
        accessToken: null,
        error: '',
        articles: Array<Article>,
        filter: 'asc',
        filterLabel: 'Newest'
      }
    },
    created: function(){
        this.accessToken = sessionStorage.getItem('token');
        this.getArticles('asc')
    },
    methods: {
      getArticles(mode: string){
        this.filterLabelFromTag(mode);
        this.loading = true;
        let config = {
          headers: {
            Authorization: `Bearer ${this.accessToken}`,
          }
        }
      axios.get('http://localhost:8000/api/article?sort='+mode, config)
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
      },
      showComments(article: Article){
        article.showComments = !article.showComments
      },
      filterLabelFromTag(tag){
        switch(tag){
          case 'asc':
            this.filterLabel = 'Newest';
          break;
          case 'desc':
            this.filterLabel = 'Oldest';
          break;
          case 'votes-asc':
            this.filterLabel = 'Least Popular';
          break;
          case 'votes-desc':
            this.filterLabel = 'Most Popular';
          break;
        }
      }

      
    }
  })
  
  interface Article {
    title: string,
    author: string,
    content: Text,
    comment: Array<any>,
    vote: Array<any>,
    showComments: Boolean,
  }
  </script>
  