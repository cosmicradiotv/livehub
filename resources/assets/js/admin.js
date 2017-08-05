import axios from 'axios'
import Vue from 'vue'

Vue.prototype.$http = axios.create()

Vue.component('show-rules-editor', () => import('./admin/show-rules/Editor'))
Vue.component('channel-settings', () => import('./admin/ChannelSettings'))

const app = new Vue({
	el: '#app'
});
