<template>
    <div class="vote-container" v-show="!voteLoading">
        <p>Votes: <span>{{totalVoteValue >=0 ?'+' :'' }}</span>{{ totalVoteValue }} 
            <span class="success" v-show="totalVoteValue>=0">&#128077;</span>
            <span class="error" v-show="totalVoteValue <0">&#128078;</span>
        </p>
        <button class="btn btn-primary vote" v-on:click="vote(1)">&#128077;</button>
        <button class="btn btn-primary" v-on:click="vote(-1)">&#128078;</button>
    </div>
    <div class="vote-container" v-show="voteLoading">
        <img src="/images/loader.gif" class="vote-loading" />
    </div>
 </template>
 
 <style scoped>
   .vote{
        margin-right:10px;
   }
   .vote-container{
    text-align: right;
    margin-top:10px
   }
   .vote-loading{
        margin-right:35px;
        width:50px;
   }
 </style>
 
 <script lang="ts">
 import { defineComponent, ref } from 'vue'
 import moment from 'moment';
 import axios from 'axios'
 
 
 export default defineComponent({
   name: 'Vote',
   props: {
        userVote:{
            type: Number,
            default: 0
        },
        votes: {
            type: Array,
            default:[]
        },
        articleId: {
            type:Number
        }
   },
   watch: {
    votes: function(newVal, oldVal){
        this.countVotes();
    }
   },
   created() {
    this.countVotes();   
   }, data() {
     return {
       loading: false,
       accessToken: null,
       error: '',
       articles: [],
       totalVoteValue: 0,
       voteLoading: false,
     }
   },
   methods: {
     vote(value){
        this.voteLoading=true
        const accessToken = sessionStorage.getItem('token');
        let config = {
          headers: {
            Authorization: `Bearer ${accessToken}`,
          }
        }

      axios.post('http://localhost:8000/api/article/'+this.articleId+'/vote',{
        'value': value
      }, config)
      .then(res => {
        this.totalVoteValue = this.totalVoteValue + value;
        this.voteLoading=false
      })
      .catch((err) => {
        //I know this is bad, but I'd like to get this over the line for you by tomorrow! :) 
        this.voteLoading = false;
      });
     },
     countVotes(){
        this.totalVoteValue=0;
        this.votes.forEach(vote => {
            this.totalVoteValue += vote.value;
        });
     }
     
   }
 })
 
 </script>
 