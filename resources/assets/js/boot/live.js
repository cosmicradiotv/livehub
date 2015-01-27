var $ = require('jquery');
require('../../../../bower_components/foundation/js/foundation/foundation');
require('../../../../bower_components/foundation/js/foundation/foundation.topbar');
var App = require('../live/app');

$(function () {
	// Run foundation
	$(document).foundation();

	// Run application
	var $target = $('[data-livehub]');
	if ($target.length) {
		module.exports = global.LiveHub = new App($target);
	}
});