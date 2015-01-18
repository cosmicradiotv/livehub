var $ = require('jquery'),
    _ = require('lodash');

var liveapp = module.exports = function ($target) {
	var app = this;

	app.$target = $target;
	app.config = null;
	app.streams = [];
	app.active = null;
	app.cruisecontrol = true;

	app.init();
};

liveapp.prototype.init = function () {
	var app = this;

	var configUrl = app.$target.data('livehub-config');
	$.ajax(configUrl).done(function (response) {
		app.config = _.omit(response, ['streams']);
		app.streams = response.streams.data;

		app.updateStreams();
		app.setCruiseControl(true);
		app.autochooseStream();
	});

	$('#cruise-control').on('click', function () {
		app.setCruiseControl(!app.cruisecontrol);
		return false;
	});
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
		$cruisecontrol.html('<span class="label success">On</span> Auto-select');
	} else {
		$cruisecontrol.html('<span class="label alert">Off</span> Auto-select')
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

liveapp.prototype.switchTo = function (streamID) {
	var app = this;

	if (!streamID) {
		$('#live-frame').attr('src', app.config.notlive);

		app.active = null;
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