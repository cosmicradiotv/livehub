import $ from 'jquery'
import App from './old/admin/app'

$(function () {
	// LEGACY START
	// Boot App
	var config = $(document.body).data('config');
	global.LiveHubAdmin = new App(config);
})