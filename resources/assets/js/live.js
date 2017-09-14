import axios from 'axios'
import Vue from 'vue'
import VueRouter from 'vue-router'

import App from './live/App'
import routes from './live/routes'

Vue.use(VueRouter)

Vue.prototype.$http = axios.create()

const router = new VueRouter({
	routes
})

const app = new Vue({
	el: '#app',
	router,
	render: h => h(App)
})
