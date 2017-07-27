import Vue from 'vue'

Vue.component('show-rules-editor', () => import('./admin/show-rules/Editor'))

const app = new Vue({
	el: '#app'
});
