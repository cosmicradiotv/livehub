var $ = require('jquery');
require('foundation');
var App = require('../admin/app');

$(function () {
	// Boot foundation
	$(document).foundation();

	// Boot App
	var config = $(document.body).data('config');
	module.exports = global.LiveHubAdmin = new App(config);
});
