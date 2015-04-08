import $ from 'jquery';
import 'foundation/foundation';
import 'foundation/foundation.topbar';
import _ from 'lodash';
import App from '../live/app';

$(function () {
	// Run application
	var $target = $('[data-livehub]');
	if ($target.length) {
		var app = module.exports = global.LiveHub = new App({
			el: $target
		});

		$.ajax($target.data('livehub-config')).done(function (response) {
			// Save most of the settings
			app.set('settings', _.omit(response, ['streams']));

			// Split streams and shows data
			var data = {
				streams: {},
				shows: {}
			};
			_.transform(response.streams.data, function (data, stream) {
				data.streams[stream.id] = _.omit(stream, ['show']);
				if(!(stream.show.data.id in data.shows)) {
					data.shows[stream.show.data.id] = stream.show.data;
				}
			}, data);

			app.set('streams', data.streams);
			app.set('shows', data.shows);

			app.set('loading', false);
		});
	}

	// Run foundation
	$(document).foundation();
});