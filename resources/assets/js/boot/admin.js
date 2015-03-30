import $ from 'jQuery';
import 'foundation';
import App from '../admin/app';

$(function () {
	// Boot foundation
	$(document).foundation();

	// Boot App
	var config = $(document.body).data('config');
	module.exports = global.LiveHubAdmin = new App(config);
});
