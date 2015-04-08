import $ from 'jquery';

var Pusher = module.exports = function (config) {
	this.type = 'interval';
	this.active = false;
	this.config = config;
	this.ondata = null;
	this.onerror = null;

	this.timeout = null;
};

Pusher.prototype.start = function () {
	this.active = true;
	this.timeout = setTimeout(this.check.bind(this), this.config.frequency * 1000);
};

Pusher.prototype.check = function () {
	var that = this;

	if (!this.active) {
		return;
	}

	$.ajax(this.config.target).done(function (response) {
		var data = {
			streams: {},
			shows: {}
		};
		_.transform(response.data, function (data, stream) {
			data.streams[stream.id] = _.omit(stream, ['show']);
			if(!(stream.show.data.id in data.shows)) {
				data.shows[stream.show.data.id] = stream.show.data;
			}
		}, data);

		if (that.ondata) {
			that.ondata(data.streams, data.shows, true);
		}

		setTimeout(that.check.bind(that), that.config.frequency * 1000);
	});

};

Pusher.prototype.cancel = function () {
	clearTimeout(this.timeout);
	this.active = false;
};