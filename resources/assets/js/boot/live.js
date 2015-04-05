import $ from 'jquery';
import 'foundation/foundation';
import 'foundation/foundation.topbar';
import App from '../live/app';

$(function () {
	// Run foundation
	$(document).foundation();

	// Run application
	var $target = $('[data-livehub]');
	if ($target.length) {
		module.exports = global.LiveHub = new App($target);
	}
});