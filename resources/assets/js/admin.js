import $ from 'jquery'
import 'foundation-sites'
import App from './old/admin/app'

$(function () {
	// Boot foundation
	$(document).foundation()

	// LEGACY START
	// Boot App
	var config = $(document.body).data('config');
	global.LiveHubAdmin = new App(config);
})