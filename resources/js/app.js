
import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';

Vue.use(Vuex)
Vue.use(VueRouter);

//import axios from 'axios'
//Vue.use(axios)
//Vue.prototype.$axios = axios

import Users from './components/showUsers.vue';

import storeData from "./index"

const store = new Vuex.Store(
   storeData
)
Vue.prototype.$store = store; 
const app = new Vue({
    el: '#app',
    storeData,
    components: {
        'Users': Users
    }
});
