var $ = require('jquery'),
    _ = require('lodash');

var liveapp = module.exports = function ($target) {
	var app = this;

	app.$target = $target;
	app.config = null;
	app.streams = [];
	app.active = null;

	app.init();
};

liveapp.prototype.init = function () {
	var app = this;

	var configUrl = app.$target.data('livehub-config');
	$.ajax(configUrl).done(function (response) {
		console.log(response);
		app.config = _.omit(response, ['streams']);
		app.streams = response.streams.data;

		app.updateStreams();
	});
};

liveapp.prototype.updateStreams = function () {
	var app = this;

	var streams = app.streams;

	app.$target.find('.live-streams-text').text('Now Live Streams: ' + _.size(streams));
	var $streamList = app.$target.find('#streams-list');
	$streamList.find('.stream').remove();
	_.map(streams, function (stream) {
		var $el = $('<li><a></a></li>').appendTo($streamList);
		$el.attr('data-stream', stream.id);
		$el.addClass('stream');

		var $link = $el.children();
		$link.on('click', function () {
			app.switchTo(stream.id);

			return false;
		});
		$link.text(stream.title);
	});

};

liveapp.prototype.switchTo = function (streamID) {
	var app = this;

	var stream = _.find(app.streams, {'id': streamID});

	if (!stream || stream.id == app.active) {
		return false;
	}


	var oldStream = _.find(app.streams, {'id': app.active});
	if (!oldStream) {
		oldStream = {'id': null, 'video_url': 'about:blank', 'chat_url': 'about:blank'};
	}

	if(stream.video_url != oldStream.video_url) {
		$('#live-frame').attr('src', stream.video_url);
	}
	if(stream.chat_url != oldStream.chat_url) {
		$('#chat-frame').attr('src', stream.chat_url);
	}


	var $streams = $('#streams-list');
	$streams.find('.active').removeClass('active');
	$streams.find('[data-stream='+ stream.id +']').addClass('active');

	app.active = stream.id;

};