import _ from 'lodash';
import $ from 'jquery';
import moment from 'moment';
import pushers from './pushers.js';
import Ractive from 'ractive';

import appTemplate from './template/layout.hbs';

var App = Ractive.extend({
	template: appTemplate,

	data: function () {
		return {
			loading: true,
			settings: {},
			streams: {},
			shows: {},

			cruiseControl: true,
			active: null,
			now: moment(),
			relativeTime: function (timestamp) {
				// Make sure this is updated every time now is updated
				var now = this.get('now'), time = moment(timestamp);
				if (time.isAfter(now)) {
					return time.from(now);
				} else {
					return 'soon';
				}
			}
		}
	},

	computed: {
		liveURL: function () {
			if (this.get('loading')) {
				return 'about:blank';
			}
			var active = this.get('active');
			if (active === null) {
				return this.get('settings.notlive');
			} else {
				return this.get('streams.' + active + '.video_url');
			}
		},
		chatUrl: function () {
			if (this.get('loading')) {
				return 'about:blank';
			}
			var active = this.get('active');
			if (active === null) {
				return 'about:blank';
			} else {
				return this.get('streams.' + active + '.chat_url');
			}
		},
		liveStreamsText: function () {
			if (this.get('loading')) {
				return 'Loading streams...';
			}
			return 'Now Live Streams: ' + _.size(this.get('streams'));
		},
		groupedStreams: function () {
			return _.transform(_.groupBy(this.get('streams'), 'show_id'), function (result, streams, key) {
				result[key] = _.sortBy(streams, 'start_time');
			});
		}
	},

	oninit: function () {
		this.on('switchCruiseControl', function (event, value) {
			this.set('cruiseControl', value);
		});

		this.on('setStream', function (event, stream_id, manual) {
			this.set('active', stream_id);
			if (manual) {
				this.fire('switchCruiseControl', null, false);
				// close dropdown
				setTimeout(function () {
					$(event.node).parents('.has-dropdown.hover').removeClass('hover');
				}, 0);
			}
		});

		// Inital loading checker
		this.observe('loading', function (newValue, oldValue, keypath) {
			if (newValue) {
				return;
			}

			this.bindPushers();
			// TODO: Autostart based on URL
			if (false) {

			} else {
				this.autoChooseStream();
			}
		});

		// Look for ended streams
		this.observe('streams', function (newValue) {
			var active = this.get('active');
			var newStreamCount = _.size(newValue);

			if (active === null && newStreamCount || !newValue[active] && this.get('cruiseControl')) {
				this.autoChooseStream();
			}
		}, {init: false});

		// Relative time updater
		var interval = setInterval(function () {
			this.set('now', moment());
		}.bind(this), 60000); // Every minute


	},

	autoChooseStream: function () {
		var firstLive = null,
			firstNext = null;

		// Select first live stream or first next to be live stream
		_.forEach(this.get('groupedStreams'), function (show) {
			_.forEach(show, function (stream) {
				if (stream.state == 'live') {
					firstLive = stream;
					return false;
				}
				if (stream.state == 'next' && !firstNext) {
					firstNext = stream;
				}
			});
			if (firstLive) {
				return false;
			}
		});

		if (!firstLive) {
			firstLive = firstNext;
			firstNext = null;
		}

		if (firstLive) {
			this.set('active', firstLive.id);
		} else {
			this.set('active', null);
		}
	},

	bindPushers: function () {
		this.pushers = _.map(this.get('settings.pushers'), function (settings, index) {
			var configured = new pushers[settings.type](settings);
			configured.ondata = function (streams, shows, overwrite) {
				if (overwrite) {
					this.set('streams', streams);
					this.set('shows', shows);
					console.log(arguments);
				}
			}.bind(this);
			configured.onerror = function () {
				configured.cancel();
				this.pushers[index + 1].start();
			}.bind(this);

			return configured;
		}, this);

		this.pushers[0].start();
	},

	pushers: []
});

export default App;


var liveapp = function ($target) {
	var app = this;

	app.$target = $target;
	app.config = null;
	app.streams = [];
	app.active = -1;
	app.cruisecontrol = true;
	app.pushers = [];

	app.init();
};
/*
 liveapp.prototype.init = function () {
 var app = this;

 var configUrl = app.$target.data('livehub-config');
 $.ajax(configUrl).done(function (response) {
 app.config = _.omit(response, ['streams']);
 app.streams = response.streams.data;

 app.updateStreams();
 app.setCruiseControl(true);
 app.autochooseStream();
 app.loadPushers();
 });

 $('#cruise-control').on('click', function () {
 app.setCruiseControl(!app.cruisecontrol);
 return false;
 });
 };

 liveapp.prototype.streamData = function (streams) {
 var app = this;

 app.streams = streams;
 app.updateStreams();

 if (!( app.active in app.streams) && app.cruisecontrol) {
 app.autochooseStream();
 }
 };

 liveapp.prototype.updateStreams = function () {
 var app = this;

 var streams = app.streams;

 app.$target.find('.live-streams-text').text('Now Live Streams: ' + _.size(streams));
 var $streamList = app.$target.find('#streams-list');
 $streamList.find('.stream').remove();
 if (_.size(streams) > 0) {
 _.map(streams, function (stream) {
 var $el = $('<li><a></a></li>').appendTo($streamList);
 $el.attr('data-stream', stream.id);
 $el.addClass('stream');

 var $link = $el.children();
 $link.on('click', function () {
 app.setCruiseControl(false);
 app.switchTo(stream.id);

 return false;
 });
 $link.text(stream.title);
 });
 } else {
 $streamList.append('<li class="stream"><a><em>No streams live</em></a></li>');
 }
 };

 liveapp.prototype.setCruiseControl = function (state) {
 var app = this;

 app.cruisecontrol = state;

 var $cruisecontrol = $('#cruise-control');
 if (app.cruisecontrol) {
 $cruisecontrol.html('<span class="label success">On</span> Auto-switch');
 } else {
 $cruisecontrol.html('<span class="label alert">Off</span> Auto-switch');
 }
 };

 liveapp.prototype.autochooseStream = function () {
 var app = this;

 var firstLive = null,
 firstNext = null;

 _.forEach(app.streams, function (stream) {
 if (stream.state == 'live') {
 firstLive = stream;
 return false;
 }
 if (stream.state == 'next' && !firstNext) {
 firstNext = stream;
 }
 });

 if (!firstLive) {
 firstLive = firstNext;
 firstNext = null;
 }

 if (firstLive) {
 app.switchTo(firstLive.id);
 // Todo: First Next
 } else {
 app.switchTo(null);
 }
 };
 */
liveapp.prototype.switchTo = function (streamID) {
	var app = this;

	if (!streamID && app.active) {
		$('#live-frame').attr('src', app.config.notlive);

		app.active = null;
		return false;
	}


	var stream = _.find(app.streams, {'id': streamID});

	if (!stream || stream.id == app.active) {
		return false;
	}


	var oldStream = _.find(app.streams, {'id': app.active});
	if (!oldStream) {
		oldStream = {'id': null, 'video_url': 'about:blank', 'chat_url': 'about:blank'};
	}

	if (stream.video_url != oldStream.video_url) {
		$('#live-frame').attr('src', stream.video_url);
	}
	if (stream.chat_url != oldStream.chat_url) {
		$('#chat-frame').attr('src', stream.chat_url);
	}


	var $streams = $('#streams-list');
	$streams.find('.active').removeClass('active');
	$streams.find('[data-stream=' + stream.id + ']').addClass('active');

	app.active = stream.id;
};

liveapp.prototype.loadPushers = function () {
	var app = this;

	app.pushers = _.map(app.config.pushers, function (settings, index) {
		var configured = new pushers[settings.type](settings);
		configured.ondata = function (data) {
			app.streamData(data);
		};
		configured.onerror = function () {
			configured.cancel();
			app.pushers[index + 1].start();
		};

		return configured;
	});

	app.pushers[0].start();
};